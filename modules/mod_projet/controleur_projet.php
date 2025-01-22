<?php
require_once dirname(__FILE__) . '/modele_projet.php';
require_once dirname(__FILE__) . '/vue_projet.php';

class Controleur_projet {
    private $modele;
    private $vue;
    private $db;
    
    public function __construct() {
        $this->modele = new Modele_projet();
        $this->vue = new Vue_projet();
        $this->db = Connexion::getConnexion();
    }
    
    public function creerProjet() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['enseignant', 'admin'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php?module=projet&action=list');
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $annee = $_POST['annee'] ?? '';
            $semestre = $_POST['semestre'] ?? '';
            $travail_groupe = isset($_POST['travail_groupe']);
            $groupe_modifiable = isset($_POST['groupe_modifiable']);
            $id_responsable = $_SESSION['user']['id'] ?? null;
            $co_responsables = $_POST['co_responsables'] ?? [];
            $intervenants = $_POST['intervenants'] ?? [];
            
            // Vérification des champs obligatoires
            if (empty($titre) || empty($semestre)) {
                $this->vue->afficherFormulaire([
                    'error' => 'Le titre et le semestre sont obligatoires',
                    'enseignants' => $this->modele->getEnseignantsDisponibles()
                ]);
                return;
            }
    
            try {
                // Vérification de l'existence de l'utilisateur
                if (!$id_responsable) {
                    throw new Exception('Session utilisateur invalide');
                }
    
                if (!$this->modele->utilisateurExiste($id_responsable)) {
                    throw new Exception('Utilisateur non trouvé ou non autorisé');
                }
                
                $id_projet = $this->modele->creerProjet(
                    $titre, 
                    $description, 
                    $annee,
                    $semestre, 
                    $travail_groupe,
                    $groupe_modifiable,
                    $id_responsable, 
                    $co_responsables, 
                    $intervenants
                );
                
                if ($id_projet) {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Projet créé avec succès'
                    ];
                    header('Location: index.php?module=projet&action=details&id=' . $id_projet);
                    return;
                }
            } catch (Exception $e) {
                error_log("Erreur lors de la création du projet: " . $e->getMessage());
                $this->vue->afficherFormulaire([
                    'error' => $e->getMessage(),
                    'enseignants' => $this->modele->getEnseignantsDisponibles()
                ]);
                return;
            }
        }
        
        $this->vue->afficherFormulaire([
            'enseignants' => $this->modele->getEnseignantsDisponibles()
        ]);
    }
    
    
    public function listerProjets() {
        $projets = $this->modele->getProjets();
        $this->vue->afficherListe(['projets' => $projets]);
    }
    
    public function modifierProjet() {
        $id = $_GET['id'] ?? 0;
        
        if (!$this->verifierDroitsProjet($id)) {
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->db->beginTransaction();
                
                $titre = $_POST['titre'] ?? '';
                $description = $_POST['description'] ?? '';
                $annee = $_POST['annee'] ?? '';
                $semestre = $_POST['semestre'] ?? '';
                $travail_groupe = isset($_POST['travail_groupe']);
                $groupe_modifiable = isset($_POST['groupe_modifiable']);
                $co_responsables = $_POST['co_responsables'] ?? [];
                $intervenants = $_POST['intervenants'] ?? [];
                
                if ($this->modele->modifierProjet($id, $titre, $description, $annee, $semestre, $travail_groupe, $groupe_modifiable)) {
                    // Mise à jour des collaborateurs
                    $this->modele->supprimerCollaborateur($id, $_SESSION['user']['id']);
                    foreach ($co_responsables as $id_enseignant) {
                        $this->modele->ajouterCollaborateur($id, $id_enseignant, 'co-responsable');
                    }
                    foreach ($intervenants as $id_enseignant) {
                        $this->modele->ajouterCollaborateur($id, $id_enseignant, 'intervenant');
                    }
                    
                    $this->db->commit();
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Projet modifié avec succès'
                    ];
                    header('Location: index.php?module=projet&action=details&id=' . $id);
                    return;
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $projet = $this->modele->getProjetById($id);
                $enseignants = $this->modele->getEnseignantsDisponibles();
                $this->vue->afficherFormulaire([
                    'error' => $e->getMessage(),
                    'projet' => $projet,
                    'enseignants' => $enseignants
                ]);
                return;
            }
        }
        
        $projet = $this->modele->getProjetById($id);
        $enseignants = $this->modele->getEnseignantsDisponibles();
        $this->vue->afficherFormulaire([
            'projet' => $projet,
            'enseignants' => $enseignants
        ]);
    }
    
    public function gererChamps() {
        $id_projet = $_GET['id'] ?? 0;
        
        if (!$this->verifierDroitsProjet($id_projet)) {
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'add-field':
                    $this->ajouterChamp($id_projet);
                    break;
                case 'edit-field':
                    $this->modifierChamp();
                    break;
                case 'delete-field':
                    $this->supprimerChamp();
                    break;
            }
        }
        
        $projet = $this->modele->getProjetById($id_projet);
        $champs = $this->modele->getChamps($id_projet);
        
        $this->vue->afficherChamps([
            'projet' => $projet,
            'champs' => $champs
        ]);
    }
    
    private function ajouterChamp($id_projet) {
        $nom = $_POST['nom'] ?? '';
        $description = $_POST['description'] ?? '';
        $type = $_POST['type'] ?? '';
        $modifiable_etudiant = isset($_POST['modifiable_etudiant']);
        $valeur = $_POST['valeur'] ?? null;
        
        if (empty($nom) || empty($type)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Le nom et le type sont obligatoires'
            ];
            return;
        }
        
        if ($this->modele->ajouterChamp($id_projet, $nom, $description, $type, $modifiable_etudiant, $valeur)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Champ ajouté avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de l\'ajout du champ'
            ];
        }
        
        header('Location: index.php?module=projet&action=champs&id=' . $id_projet);
        exit;
    }
    
    private function modifierChamp() {
        $id_champ = $_GET['id'] ?? 0;
        $nom = $_POST['nom'] ?? '';
        $description = $_POST['description'] ?? '';
        $type = $_POST['type'] ?? '';
        $modifiable_etudiant = isset($_POST['modifiable_etudiant']);
        $valeur = $_POST['valeur'] ?? null;
        
        if ($this->modele->modifierChamp($id_champ, $nom, $description, $type, $modifiable_etudiant, $valeur)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Champ modifié avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la modification du champ'
            ];
        }
        
        $champ = $this->modele->getChampById($id_champ);
        header('Location: index.php?module=projet&action=champs&id=' . $champ['id_projet']);
        exit;
    }

    public function afficherDetails() {
        $id = $_GET['id'] ?? 0;
        
        if (!$id) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID de projet manquant'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        $projet = $this->modele->getProjetById($id);
        if (!$projet) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Projet introuvable'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        // Récupérer les informations supplémentaires
        $groupes = $this->modele->getGroupesProjet($id);
        $evaluations = $this->modele->getEvaluationsProjet($id);
        $champs = $this->modele->getChamps($id);
        
        $this->vue->afficherDetails([
            'projet' => $projet,
            'groupes' => $groupes,
            'evaluations' => $evaluations,
            'champs' => $champs
        ]);
    }
    
    
    
    public function gererGroupes() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['enseignant', 'admin'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
    
        $id_projet = $_GET['id'] ?? 0;
        $projet = $this->modele->getProjetById($id_projet);
        
        if (!$projet) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Projet introuvable'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'create-group':
                    $this->creerGroupe($id_projet);
                    break;
                case 'edit-group':
                    $this->modifierGroupe();
                    break;
                case 'add-student':
                    $this->ajouterEtudiant();
                    break;
                case 'remove-student':
                    $this->retirerEtudiant();
                    break;
            }
        }
    
        $groupes = $this->modele->getGroupesProjet($id_projet);
        $etudiants = $this->modele->getEtudiantsDisponibles($id_projet);
        
        $this->vue->afficherGroupes([
            'projet' => $projet,
            'groupes' => $groupes,
            'etudiants' => $etudiants
        ]);
    }
    
    private function creerGroupe($id_projet) {
        $titre = $_POST['titre'] ?? '';
        
        if (empty($titre)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Le titre du groupe est obligatoire'
            ];
            return;
        }
        
        if ($this->modele->creerGroupe($id_projet, $titre)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Groupe créé avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la création du groupe'
            ];
        }
        
        header('Location: index.php?module=projet&action=groupes&id=' . $id_projet);
        exit;
    }
    
    private function verifierDroitsProjet($id_projet) {
        $projet = $this->modele->getProjetById($id_projet);
        
        if (!$projet) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Projet introuvable'
            ];
            header('Location: index.php?module=projet&action=list');
            return false;
        }
        
        // Vérifier si l'utilisateur est responsable ou co-responsable
        if ($projet['id_responsable'] !== $_SESSION['user']['id'] && 
            !in_array($_SESSION['user']['id'], explode(',', $projet['co_responsables']))) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour modifier ce projet'
            ];
            header('Location: index.php?module=projet&action=list');
            return false;
        }
        
        return true;
    }
    
    public function gererSoutenances() {
        $id_projet = $_GET['id'] ?? 0;
        
        if (!$this->verifierDroitsProjet($id_projet)) {
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_GET['action'] ?? '';
            
            switch ($action) {
                case 'add-soutenance':
                    $this->ajouterSoutenance($id_projet);
                    break;
                case 'add-passage':
                    $this->ajouterPassage();
                    break;
                case 'evaluer-passage':
                    $this->evaluerPassage();
                    break;
            }
        }
        
        $projet = $this->modele->getProjetById($id_projet);
        $soutenances = $this->modele->getSoutenances($id_projet);
        $groupes = $this->modele->getGroupesProjet($id_projet);
        
        $this->vue->afficherSoutenances([
            'projet' => $projet,
            'soutenances' => $soutenances,
            'groupes' => $groupes
        ]);
    }
}