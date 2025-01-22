<?php
require_once 'modele_groupe.php';
require_once 'vue_groupe.php';

class Controleur_groupe {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_groupe();
        $this->vue = new Vue_groupe();
    }
    
    public function creerGroupe() {
        // Vérifier que l'utilisateur est responsable du projet
        if (!in_array($_SESSION['user']['role'], ['enseignant', 'admin'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour créer des groupes'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_projet = $_POST['id_projet'] ?? 0;
            $titre = $_POST['titre'] ?? '';
            $titre_modifiable = isset($_POST['titre_modifiable']);
            $image_modifiable = isset($_POST['image_modifiable']);
            
            if ($this->modele->creerGroupe($id_projet, $titre, $titre_modifiable, $image_modifiable)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Groupe créé avec succès'
                ];
                header('Location: index.php?module=groupe&action=list&id_projet=' . $id_projet);
                exit;
            } else {
                $this->vue->afficherFormulaire([
                    'error' => 'Erreur lors de la création du groupe',
                    'projets' => $this->modele->getProjetsPourEnseignant($_SESSION['user']['id'])
                ]);
            }
        } else {
            $this->vue->afficherFormulaire([
                'projets' => $this->modele->getProjetsPourEnseignant($_SESSION['user']['id'])
            ]);
        }
    }
    
    public function modifierGroupe() {
        $id_groupe = $_GET['id'] ?? 0;
        $groupe = $this->modele->getGroupeById($id_groupe);
        
        if (!$groupe) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Groupe non trouvé'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        // Vérifier les droits de modification
        if (!$this->modele->peutModifierGroupe($id_groupe, $_SESSION['user']['id'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour modifier ce groupe'
            ];
            header('Location: index.php?module=groupe&action=list&id_projet=' . $groupe['id_projet']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $titre_modifiable = isset($_POST['titre_modifiable']);
            $image_modifiable = isset($_POST['image_modifiable']);
            
            if ($this->modele->modifierGroupe($id_groupe, $titre, $titre_modifiable, $image_modifiable)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Groupe modifié avec succès'
                ];
                header('Location: index.php?module=groupe&action=list&id_projet=' . $groupe['id_projet']);
                exit;
            } else {
                $this->vue->afficherFormulaire([
                    'error' => 'Erreur lors de la modification du groupe',
                    'groupe' => $groupe
                ]);
            }
        } else {
            $this->vue->afficherFormulaire(['groupe' => $groupe]);
        }
    }
    
    public function ajouterEtudiant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_groupe = $_POST['id_groupe'] ?? 0;
            $id_etudiant = $_POST['id_etudiant'] ?? 0;
            
            $groupe = $this->modele->getGroupeById($id_groupe);
            if (!$groupe) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Groupe non trouvé'
                ];
                header('Location: index.php?module=projet&action=list');
                exit;
            }
            
            // Vérifier les droits
            if (!$this->modele->peutModifierGroupe($id_groupe, $_SESSION['user']['id'])) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Vous n\'avez pas les droits pour modifier ce groupe'
                ];
                header('Location: index.php?module=groupe&action=list&id_projet=' . $groupe['id_projet']);
                exit;
            }
            
            if ($this->modele->ajouterEtudiant($id_groupe, $id_etudiant)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Étudiant ajouté avec succès'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Erreur lors de l\'ajout de l\'étudiant'
                ];
            }
            header('Location: index.php?module=groupe&action=list&id_projet=' . $groupe['id_projet']);
            exit;
        }
    }
    
    public function retirerEtudiant() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_groupe = $_POST['id_groupe'] ?? 0;
            $id_etudiant = $_POST['id_etudiant'] ?? 0;
            
            $groupe = $this->modele->getGroupeById($id_groupe);
            if (!$groupe) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Groupe non trouvé'
                ];
                header('Location: index.php?module=projet&action=list');
                exit;
            }
            
            // Vérifier les droits
            if (!$this->modele->peutModifierGroupe($id_groupe, $_SESSION['user']['id'])) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Vous n\'avez pas les droits pour modifier ce groupe'
                ];
                header('Location: index.php?module=groupe&action=list&id_projet=' . $groupe['id_projet']);
                exit;
            }
            
            if ($this->modele->retirerEtudiant($id_groupe, $id_etudiant)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Étudiant retiré avec succès'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Erreur lors du retrait de l\'étudiant'
                ];
            }
            header('Location: index.php?module=groupe&action=list&id_projet=' . $groupe['id_projet']);
            exit;
        }
    }
    
    public function listerGroupes() {
        $id_projet = $_GET['id_projet'] ?? 0;
        
        if (!$id_projet) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID du projet manquant'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        $groupes = $this->modele->getGroupesByProjet($id_projet);
        $etudiants = $this->modele->getEtudiantsDisponibles($id_projet);
        $this->vue->afficherListe([
            'groupes' => $groupes,
            'etudiants' => $etudiants,
            'id_projet' => $id_projet,
            'est_responsable' => $this->modele->estResponsableProjet($id_projet, $_SESSION['user']['id'])
        ]);
    }
}