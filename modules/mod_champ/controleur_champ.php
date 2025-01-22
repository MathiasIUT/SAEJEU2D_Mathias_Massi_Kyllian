<?php
require_once 'modele_champ.php';
require_once 'vue_champ.php';

class Controleur_champ {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_champ();
        $this->vue = new Vue_champ();
    }
    
    public function creerChamp() {
        // Vérifier que l'utilisateur est responsable du projet
        if (!$this->modele->estResponsableProjet($_SESSION['user']['id'], $_GET['id_projet'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour créer des champs sur ce projet'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_projet = $_POST['id_projet'] ?? 0;
            $nom = $_POST['nom'] ?? '';
            $description = $_POST['description'] ?? '';
            $type = $_POST['type'] ?? '';
            $obligatoire = isset($_POST['obligatoire']);
            $modifiable_groupe = isset($_POST['modifiable_groupe']);
            $ordre = $_POST['ordre'] ?? 0;
            
            if ($this->modele->creerChamp($id_projet, $nom, $description, $type, $obligatoire, $modifiable_groupe, $ordre)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Champ créé avec succès'
                ];
                header('Location: index.php?module=projet&action=edit&id=' . $id_projet);
                exit;
            } else {
                $this->vue->afficherFormulaire([
                    'error' => 'Erreur lors de la création du champ',
                    'id_projet' => $id_projet
                ]);
            }
        } else {
            $this->vue->afficherFormulaire(['id_projet' => $_GET['id_projet']]);
        }
    }
    
    public function modifierChamp() {
        $id_champ = $_GET['id'] ?? 0;
        $champ = $this->modele->getChampById($id_champ);
        
        if (!$champ) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Champ non trouvé'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        // Vérifier que l'utilisateur est responsable du projet
        if (!$this->modele->estResponsableProjet($_SESSION['user']['id'], $champ['id_projet'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour modifier ce champ'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $description = $_POST['description'] ?? '';
            $type = $_POST['type'] ?? '';
            $obligatoire = isset($_POST['obligatoire']);
            $modifiable_groupe = isset($_POST['modifiable_groupe']);
            $ordre = $_POST['ordre'] ?? 0;
            
            if ($this->modele->modifierChamp($id_champ, $nom, $description, $type, $obligatoire, $modifiable_groupe, $ordre)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Champ modifié avec succès'
                ];
                header('Location: index.php?module=projet&action=edit&id=' . $champ['id_projet']);
                exit;
            } else {
                $this->vue->afficherFormulaire([
                    'error' => 'Erreur lors de la modification du champ',
                    'champ' => $champ
                ]);
            }
        } else {
            $this->vue->afficherFormulaire(['champ' => $champ]);
        }
    }
    
    public function supprimerChamp() {
        $id_champ = $_GET['id'] ?? 0;
        $champ = $this->modele->getChampById($id_champ);
        
        if (!$champ) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Champ non trouvé'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        // Vérifier que l'utilisateur est responsable du projet
        if (!$this->modele->estResponsableProjet($_SESSION['user']['id'], $champ['id_projet'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour supprimer ce champ'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }

        if ($this->modele->supprimerChamp($id_champ)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Champ supprimé avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la suppression du champ'
            ];
        }
        
        header('Location: index.php?module=projet&action=edit&id=' . $champ['id_projet']);
        exit;
    }
    
    public function listerChamps() {
        $id_projet = $_GET['id_projet'] ?? 0;
        
        if (!$id_projet) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID du projet manquant'
            ];
            header('Location: index.php?module=projet&action=list');
            exit;
        }
        
        $champs = $this->modele->getChampsByProjet($id_projet);
        $this->vue->afficherListe(['champs' => $champs, 'id_projet' => $id_projet]);
    }
}