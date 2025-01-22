<?php
require_once 'modele_ressource.php';
require_once 'vue_ressource.php';

class Controleur_ressource {
    private $modele;
    private $vue;
    private $upload_dir;
    
    public function __construct() {
        $this->modele = new Modele_ressource();
        $this->vue = new Vue_ressource();
        $this->upload_dir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'ressources';
        $this->initializeUploadDirectory();
    }
    
    private function initializeUploadDirectory() {
        if (!file_exists($this->upload_dir)) {
            if (!mkdir($this->upload_dir, 0777, true)) {
                error_log("Impossible de créer le dossier: " . $this->upload_dir);
                return false;
            }
            chmod($this->upload_dir, 0777);
        } else if (!is_writable($this->upload_dir)) {
            chmod($this->upload_dir, 0777);
        }
        return true;
    }

    public function ajouterRessource() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'enseignant' && $_SESSION['user']['role'] !== 'admin')) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Accès non autorisé'
            ];
            header('Location: index.php');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $type = $_POST['type'] ?? '';
            $lien = $_POST['lien'] ?? '';
            
            if (empty($titre) || empty($type)) {
                $this->vue->afficherFormulaire([
                    'error' => 'Veuillez remplir tous les champs obligatoires'
                ]);
                return;
            }
            
            // Gestion du fichier uploadé
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
                    
                    // Vérifier que les dossiers existent et sont accessibles
                    if (!$this->initializeUploadDirectory()) {
                        throw new Exception('Erreur lors de la préparation du dossier d\'upload');
                    }
                    
                    // Génération d'un nom de fichier unique
                    $fichier = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $_FILES['fichier']['name']);
                    $upload_path = $this->upload_dir . DIRECTORY_SEPARATOR . $fichier;
                    
                    // Log pour le débogage
                    error_log("Tentative d'upload du fichier vers: " . $upload_path);
                    error_log("Fichier temporaire: " . $_FILES['fichier']['tmp_name']);
                    error_log("Taille du fichier: " . $_FILES['fichier']['size']);
                    
                    // Vérifier si le fichier temporaire existe
                    if (!file_exists($_FILES['fichier']['tmp_name'])) {
                        throw new Exception('Le fichier temporaire n\'existe pas');
                    }
                    
                    // Vérifier les permissions du dossier de destination
                    if (!is_writable($this->upload_dir)) {
                        throw new Exception('Le dossier de destination n\'est pas accessible en écriture');
                    }
                    
                    // Tentative de déplacement du fichier
                    if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $upload_path)) {
                        $error = error_get_last();
                        throw new Exception('Erreur lors du téléchargement du fichier: ' . ($error['message'] ?? 'Erreur inconnue'));
                    }
                    
                    // Définir les permissions du fichier
                    chmod($upload_path, 0644);
                    
                } catch (Exception $e) {
                    error_log("Erreur lors de l'upload: " . $e->getMessage());
                    $this->vue->afficherFormulaire([
                        'error' => $e->getMessage()
                    ]);
                    return;
                }
            }
            
            if ($this->modele->ajouterRessource($titre, $description, $type, $lien, $fichier)) {
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