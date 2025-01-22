<?php
require_once dirname(__FILE__) . '/modele_ressource.php';
require_once dirname(__FILE__) . '/vue_ressource.php';

class Controleur_ressource {
    private $modele;
    private $vue;
    private $upload_dir;
    
    public function __construct() {
        $this->modele = new Modele_ressource();
        $this->vue = new Vue_ressource();
        $this->upload_dir = ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'ressources';
        $this->initializeUploadDirectory();
    }
    
    private function initializeUploadDirectory() {
        // Créer d'abord le dossier uploads s'il n'existe pas
        $parent_dir = dirname($this->upload_dir);
        if (!file_exists($parent_dir)) {
            if (!mkdir($parent_dir, 0777, true)) {
                error_log("Impossible de créer le dossier parent: " . $parent_dir);
                return false;
            }
            chmod($parent_dir, 0777);
        }

        // Créer ensuite le dossier ressources
        if (!file_exists($this->upload_dir)) {
            if (!mkdir($this->upload_dir, 0777, true)) {
                error_log("Impossible de créer le dossier: " . $this->upload_dir);
                return false;
            }
            chmod($this->upload_dir, 0777);
        }

        // Vérifier les permissions
        if (!is_writable($this->upload_dir)) {
            chmod($this->upload_dir, 0777);
            if (!is_writable($this->upload_dir)) {
                error_log("Le dossier n'est pas accessible en écriture: " . $this->upload_dir);
                return false;
            }
        }

        return true;
    }

    private function hasAccess($roles) {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $roles)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php');
            exit;
        }
        return true;
    }

    private function handleFileUpload() {
        $fichier = null;
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
            try {
                // Vérification de la taille du fichier (50MB max)
                if ($_FILES['fichier']['size'] > 50 * 1024 * 1024) {
                    throw new Exception('Le fichier est trop volumineux. Taille maximale : 50MB');
                }

                $extension = strtolower(pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION));
                $allowed_types = ['pdf', 'doc', 'docx', 'txt', 'zip', 'rar'];
                
                if (!in_array($extension, $allowed_types)) {
                    throw new Exception('Type de fichier non autorisé. Types acceptés : ' . implode(', ', $allowed_types));
                }
                
                // Vérifier que le dossier d'upload existe et est accessible
                if (!$this->initializeUploadDirectory()) {
                    throw new Exception('Erreur lors de la préparation du dossier d\'upload');
                }
                
                // Génération d'un nom de fichier unique
                $fichier = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $_FILES['fichier']['name']);
                $upload_path = $this->upload_dir . DIRECTORY_SEPARATOR . $fichier;
                
                if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $upload_path)) {
                    $error = error_get_last();
                    throw new Exception('Erreur lors du téléchargement du fichier: ' . ($error['message'] ?? 'Erreur inconnue'));
                }
                
                chmod($upload_path, 0644);
                
            } catch (Exception $e) {
                error_log("Erreur d'upload: " . $e->getMessage());
                $this->vue->afficherFormulaire([
                    'error' => $e->getMessage()
                ]);
                return null;
            }
        }
        return $fichier;
    }

    public function ajouterRessource() {
        if (!$this->hasAccess(['enseignant', 'admin'])) return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $type = $_POST['type'] ?? '';
            $lien = $_POST['lien'] ?? '';
            $promotion = $_POST['promotion'] ?? 'BUT1';
            
            if (empty($titre) || empty($type) || empty($promotion)) {
                $this->vue->afficherFormulaire([
                    'error' => 'Veuillez remplir tous les champs obligatoires'
                ]);
                return;
            }

            $fichier = $this->handleFileUpload();

            if ($this->modele->ajouterRessource($titre, $description, $type, $lien, $fichier, $promotion)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Ressource ajoutée avec succès'
                ];
                header('Location: index.php?module=ressource&action=list');
                exit;
            }
            
            $this->vue->afficherFormulaire([
                'error' => 'Erreur lors de l\'ajout de la ressource'
            ]);
        } else {
            $this->vue->afficherFormulaire();
        }
    }

    public function modifierRessource() {
        if (!$this->hasAccess(['enseignant', 'admin'])) return;

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID de ressource manquant'
            ];
            header('Location: index.php?module=ressource&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $type = $_POST['type'] ?? '';
            $lien = $_POST['lien'] ?? '';
            $promotion = $_POST['promotion'] ?? 'BUT1';
            
            if (empty($titre) || empty($type) || empty($promotion)) {
                $this->vue->afficherFormulaire([
                    'error' => 'Veuillez remplir tous les champs obligatoires',
                    'ressource' => $_POST
                ]);
                return;
            }

            $fichier = $this->handleFileUpload();

            if ($this->modele->modifierRessource($id, $titre, $description, $type, $lien, $promotion, $fichier)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Ressource modifiée avec succès'
                ];
                header('Location: index.php?module=ressource&action=list');
                exit;
            }
            
            $this->vue->afficherFormulaire([
                'error' => 'Erreur lors de la modification de la ressource',
                'ressource' => $_POST
            ]);
        } else {
            $ressource = $this->modele->getRessourceById($id);
            if (!$ressource) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Ressource introuvable'
                ];
                header('Location: index.php?module=ressource&action=list');
                exit;
            }
            $this->vue->afficherFormulaire(['ressource' => $ressource]);
        }
    }

    public function supprimerRessource() {
        if (!$this->hasAccess(['enseignant', 'admin'])) return;

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID de ressource manquant'
            ];
            header('Location: index.php?module=ressource&action=list');
            exit;
        }

        // Récupérer les informations de la ressource avant la suppression
        $ressource = $this->modele->getRessourceById($id);
        if (!$ressource) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Ressource introuvable'
            ];
            header('Location: index.php?module=ressource&action=list');
            exit;
        }

        // Supprimer le fichier physique si il existe
        if (!empty($ressource['fichier'])) {
            $chemin_fichier = $this->upload_dir . DIRECTORY_SEPARATOR . $ressource['fichier'];
            if (file_exists($chemin_fichier)) {
                unlink($chemin_fichier);
            }
        }

        // Supprimer l'enregistrement de la base de données
        if ($this->modele->supprimerRessource($id)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Ressource supprimée avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Erreur lors de la suppression de la ressource'
            ];
        }

        header('Location: index.php?module=ressource&action=list');
        exit;
    }

    public function toggleMiseEnAvant() {
        if (!$this->hasAccess(['enseignant'])) return;

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID de ressource manquant'
            ];
            header('Location: index.php?module=ressource&action=list');
            exit;
        }

        if ($this->modele->toggleMiseEnAvant($id)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Statut de mise en avant modifié avec succès'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous devez être responsable du projet pour mettre en avant une ressource'
            ];
        }

        header('Location: index.php?module=ressource&action=list');
        exit;
    }

    public function listerRessources() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous devez être connecté pour voir les ressources'
            ];
            header('Location: index.php?module=connexion');
            exit;
        }

        $ressources = $this->modele->getRessources();
        $this->vue->afficherListe(['ressources' => $ressources]);
    }
}