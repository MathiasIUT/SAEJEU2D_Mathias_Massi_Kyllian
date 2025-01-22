<?php
require_once 'modele_soutenance.php';
require_once 'vue_soutenance.php';

class Controleur_soutenance {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_soutenance();
        $this->vue = new Vue_soutenance();
    }
    
    public function listerSoutenances() {
        if ($_SESSION['user']['role'] === 'etudiant') {
            $soutenances = $this->modele->getSoutenancesEtudiant($_SESSION['user']['id']);
        } else {
            $soutenances = $this->modele->getSoutenancesEnseignant($_SESSION['user']['id']);
        }
        $this->vue->afficherListe($soutenances);
    }
    
    public function planifierPassage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_soutenance = $_POST['id_soutenance'] ?? 0;
            $id_groupe = $_POST['id_groupe'] ?? 0;
            $heure_passage = $_POST['heure_passage'] ?? '';
            
            if (empty($id_soutenance) || empty($id_groupe) || empty($heure_passage)) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Tous les champs sont requis'
                ];
            } else {
                if ($this->modele->planifierPassage($id_soutenance, $id_groupe, $heure_passage)) {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Passage planifié avec succès'
                    ];
                } else {
                    $_SESSION['flash'] = [
                        'type' => 'danger',
                        'message' => 'Erreur lors de la planification'
                    ];
                }
            }
            header('Location: index.php?module=soutenance&action=list');
            exit;
        }
        
        $id_soutenance = $_GET['id'] ?? 0;
        $soutenance = $this->modele->getSoutenanceById($id_soutenance);
        
        if (!$soutenance) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Soutenance non trouvée'
            ];
            header('Location: index.php?module=soutenance&action=list');
            exit;
        }
        
        $groupes = $this->modele->getGroupesDisponibles($id_soutenance);
        
        $this->vue->afficherPlanification([
            'soutenance' => $soutenance,
            'groupes' => $groupes
        ]);
    }
    
    public function supprimerSoutenance() {
        $id_soutenance = $_GET['id'] ?? 0;
        
        if (empty($id_soutenance)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID de soutenance manquant'
            ];
            header('Location: index.php?module=soutenance&action=list');
            exit;
        }
        
        // Vérifier que l'utilisateur a le droit de supprimer cette soutenance
        if (!$this->modele->peutModifierSoutenance($id_soutenance, $_SESSION['user']['id'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour supprimer cette soutenance'
            ];
            header('Location: index.php?module=soutenance&action=list');
            exit;
        }
        
        if ($this->modele->supprimerSoutenance($id_soutenance)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Soutenance supprimée avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la suppression de la soutenance'
            ];
        }
        
        header('Location: index.php?module=soutenance&action=list');
        exit;
    }
}