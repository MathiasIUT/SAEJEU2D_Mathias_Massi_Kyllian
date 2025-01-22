<?php
require_once 'modele_evaluation.php';
require_once 'vue_evaluation.php';

class Controleur_evaluation {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_evaluation();
        $this->vue = new Vue_evaluation();
    }
    
    public function listerEvaluations() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous devez être connecté pour voir les évaluations'
            ];
            header('Location: index.php?module=connexion');
            exit;
        }

        $evaluations = [];
        if ($_SESSION['user']['role'] === 'etudiant') {
            $evaluations = $this->modele->getEvaluationsEtudiant($_SESSION['user']['id']);
        } else {
            $evaluations = $this->modele->getEvaluationsEnseignant($_SESSION['user']['id']);
        }
        
        $this->vue->afficherListe(['evaluations' => $evaluations]);
    }

    public function creerEvaluation() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['enseignant', 'admin'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php?module=evaluation&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_projet = $_POST['id_projet'] ?? null;
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $coefficient = floatval($_POST['coefficient'] ?? 1.0);
            $type = $_POST['type'] ?? 'groupe';
            $id_rendu = !empty($_POST['id_rendu']) ? $_POST['id_rendu'] : null;
            
            // Validation des données
            if (empty($id_projet) || empty($titre)) {
                $this->vue->afficherFormulaire([
                    'error' => 'Veuillez remplir tous les champs obligatoires',
                    'projets' => $this->modele->getProjetsEnseignant($_SESSION['user']['id'])
                ]);
                return;
            }

            // Validation du type
            if (!in_array($type, ['groupe', 'individuel'])) {
                $this->vue->afficherFormulaire([
                    'error' => 'Type d\'évaluation invalide',
                    'projets' => $this->modele->getProjetsEnseignant($_SESSION['user']['id'])
                ]);
                return;
            }

            // Validation du coefficient
            if ($coefficient <= 0 || $coefficient > 10) {
                $this->vue->afficherFormulaire([
                    'error' => 'Le coefficient doit être compris entre 0 et 10',
                    'projets' => $this->modele->getProjetsEnseignant($_SESSION['user']['id'])
                ]);
                return;
            }
            
            if ($this->modele->creerEvaluation($id_projet, $titre, $description, $coefficient, $type, $id_rendu)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Évaluation créée avec succès'
                ];
                header('Location: index.php?module=evaluation&action=list');
                exit;
            } else {
                $this->vue->afficherFormulaire([
                    'error' => 'Erreur lors de la création de l\'évaluation',
                    'projets' => $this->modele->getProjetsEnseignant($_SESSION['user']['id'])
                ]);
            }
        } else {
            $this->vue->afficherFormulaire([
                'projets' => $this->modele->getProjetsEnseignant($_SESSION['user']['id'])
            ]);
        }
    }

    public function deleguerEvaluation() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['enseignant', 'admin'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php?module=evaluation&action=list');
            exit;
        }

        $id_evaluation = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_enseignant = $_POST['id_enseignant'] ?? 0;
            
            if (empty($id_enseignant)) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Veuillez sélectionner un enseignant'
                ];
                header('Location: index.php?module=evaluation&action=delegate&id=' . $id_evaluation);
                exit;
            }
            
            if ($this->modele->deleguerEvaluation($id_evaluation, $id_enseignant)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Évaluation déléguée avec succès'
                ];
                header('Location: index.php?module=evaluation&action=list');
                exit;
            }
            
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la délégation'
            ];
            header('Location: index.php?module=evaluation&action=delegate&id=' . $id_evaluation);
            exit;
        }
        
        $evaluation = $this->modele->getEvaluationById($id_evaluation);
        if (!$evaluation) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Évaluation introuvable'
            ];
            header('Location: index.php?module=evaluation&action=list');
            exit;
        }
        
        $this->vue->afficherDelegation([
            'evaluation' => $evaluation,
            'enseignants' => $this->modele->getEnseignantsDisponibles($id_evaluation)
        ]);
    }

    public function noterEvaluation() {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['enseignant', 'admin'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php?module=evaluation&action=list');
            exit;
        }

        $id_evaluation = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notes = $_POST['notes'] ?? [];
            $commentaires = $_POST['commentaires'] ?? [];
            $success = true;
            
            foreach ($notes as $id_etudiant => $note) {
                $commentaire = $commentaires[$id_etudiant] ?? '';
                
                // Validation de la note
                if (!is_numeric($note) || $note < 0 || $note > 20) {
                    $_SESSION['flash'] = [
                        'type' => 'danger',
                        'message' => 'Les notes doivent être comprises entre 0 et 20'
                    ];
                    header('Location: index.php?module=evaluation&action=note&id=' . $id_evaluation);
                    exit;
                }
                
                if (!$this->modele->noterEvaluation($id_evaluation, $id_etudiant, $note, $commentaire)) {
                    $success = false;
                }
            }
            
            if ($success) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Notes enregistrées avec succès'
                ];
            } else {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Erreur lors de l\'enregistrement des notes'
                ];
            }
            header('Location: index.php?module=evaluation&action=list');
            exit;
        }
        
        $evaluation = $this->modele->getEvaluationById($id_evaluation);
        if (!$evaluation) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Évaluation introuvable'
            ];
            header('Location: index.php?module=evaluation&action=list');
            exit;
        }
        
        $this->vue->afficherNotation([
            'evaluation' => $evaluation,
            'etudiants' => $this->modele->getEtudiantsEvaluation($id_evaluation)
        ]);
    }
}