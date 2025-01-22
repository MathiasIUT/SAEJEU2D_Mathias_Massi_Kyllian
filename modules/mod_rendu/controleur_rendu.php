<?php
require_once dirname(__FILE__) . '/modele_rendu.php';
require_once dirname(__FILE__) . '/vue_rendu.php';

class Controleur_rendu {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_rendu();
        $this->vue = new Vue_rendu();
    }
    
    public function listerRendus() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous devez être connecté pour voir les rendus.'
            ];
            header('Location: index.php?module=auth&action=login');
            exit;
        }

        $rendus = [];
        if ($_SESSION['user']['role'] === 'etudiant') {
            $rendus = $this->modele->getRendusEtudiant($_SESSION['user']['id']);
        } else {
            $rendus = $this->modele->getRendus();
        }
        
        $this->vue->afficherListe(['rendus' => $rendus]);
    }

    public function evaluerRendu() {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'enseignant' && $_SESSION['user']['role'] !== 'admin')) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous n\'avez pas les droits pour évaluer les rendus.'
            ];
            header('Location: index.php?module=rendu&action=list');
            exit;
        }

        $id_rendu = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = $_POST['note'] ?? null;
            $commentaire = $_POST['commentaire'] ?? '';
            
            if ($note === null || !is_numeric($note) || $note < 0 || $note > 20) {
                $this->vue->afficherEvaluation([
                    'error' => 'La note doit être comprise entre 0 et 20',
                    'rendu' => $this->modele->getRenduById($id_rendu)
                ]);
                return;
            }
            
            if ($this->modele->evaluerRendu($id_rendu, $note, $commentaire)) {
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Évaluation enregistrée avec succès'
                ];
                header('Location: index.php?module=rendu&action=list');
                exit;
            }
            
            $this->vue->afficherEvaluation([
                'error' => 'Erreur lors de l\'enregistrement de l\'évaluation',
                'rendu' => $this->modele->getRenduById($id_rendu)
            ]);
        } else {
            $rendu = $this->modele->getRenduById($id_rendu);
            if (!$rendu) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Rendu introuvable'
                ];
                header('Location: index.php?module=rendu&action=list');
                exit;
            }
            $this->vue->afficherEvaluation(['rendu' => $rendu]);
        }
    }

    public function telechargerRendu() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous devez être connecté pour télécharger un rendu.'
            ];
            header('Location: index.php?module=auth&action=login');
            exit;
        }
    
        $id_rendu = $_GET['id'] ?? 0;
    
        if (!$id_rendu || !is_numeric($id_rendu)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'ID de rendu invalide.'
            ];
            header('Location: index.php?module=rendu&action=list');
            exit;
        }
    
        $rendu = $this->modele->getRenduById($id_rendu);
        if (!$rendu) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Rendu introuvable.'
            ];
            header('Location: index.php?module=rendu&action=list');
            exit;
        }
    
        $fichiers = json_decode($rendu['fichiers'], true);
        if (empty($fichiers) || !is_array($fichiers)) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Aucun fichier trouvé pour ce rendu.'
            ];
            header('Location: index.php?module=rendu&action=list');
            exit;
        }
    
        if (count($fichiers) === 1) {
            $fichier = $fichiers[0];
            $cheminFichier = ROOT_PATH . '/uploads/rendus/' . $fichier;
    
            if (!file_exists($cheminFichier)) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Fichier introuvable.'
                ];
                header('Location: index.php?module=rendu&action=list');
                exit;
            }
    
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($fichier) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($cheminFichier));
            readfile($cheminFichier);
            exit;
        } else {
            $zip = new ZipArchive();
            $cheminZip = ROOT_PATH . '/uploads/rendus/rendu_' . $id_rendu . '.zip';
    
            if ($zip->open($cheminZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                $_SESSION['flash'] = [
                    'type' => 'danger',
                    'message' => 'Impossible de créer l\'archive ZIP.'
                ];
                header('Location: index.php?module=rendu&action=list');
                exit;
            }
    
            foreach ($fichiers as $fichier) {
                $cheminFichier = ROOT_PATH . '/uploads/rendus/' . $fichier;
                if (file_exists($cheminFichier)) {
                    $zip->addFile($cheminFichier, $fichier);
                }
            }
            $zip->close();
    
            header('Content-Description: File Transfer');
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="rendu_' . $id_rendu . '.zip"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($cheminZip));
            readfile($cheminZip);
    
            unlink($cheminZip);
            exit;
        }
    }
    
    public function soumettreRendu() {
        if ($_SESSION['user']['role'] !== 'etudiant') {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Seuls les étudiants peuvent soumettre des rendus'
            ];
            header('Location: index.php?module=rendu&action=list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_projet = $_POST['id_projet'] ?? 0;
            $commentaire = $_POST['commentaire'] ?? '';
            
            if (!$id_projet) {
                $this->vue->afficherFormulaire([
                    'error' => 'Veuillez sélectionner un projet',
                    'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
                ]);
                return;
            }

            $id_groupe = $this->modele->getGroupeEtudiant($_SESSION['user']['id'], $id_projet);
            if (!$id_groupe) {
                $this->vue->afficherFormulaire([
                    'error' => 'Vous n\'êtes pas membre d\'un groupe pour ce projet',
                    'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
                ]);
                return;
            }

            if (empty($_FILES['fichiers']['name'][0])) {
                $this->vue->afficherFormulaire([
                    'error' => 'Veuillez sélectionner au moins un fichier',
                    'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
                ]);
                return;
            }

            $upload_dir = ROOT_PATH . '/uploads/rendus/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $fichiers_sauvegardes = [];
            $max_file_size = 50 * 1024 * 1024; // 50 MB
            $allowed_types = ['pdf', 'zip', 'doc', 'docx', 'txt', 'odt'];

            foreach ($_FILES['fichiers']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['fichiers']['name'][$key];
                $file_size = $_FILES['fichiers']['size'][$key];
                $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if ($file_size > $max_file_size) {
                    $this->vue->afficherFormulaire([
                        'error' => 'Le fichier ' . $file_name . ' dépasse la taille maximale autorisée (50 MB)',
                        'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
                    ]);
                    return;
                }

                if (!in_array($file_type, $allowed_types)) {
                    $this->vue->afficherFormulaire([
                        'error' => 'Le type de fichier ' . $file_type . ' n\'est pas autorisé',
                        'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
                    ]);
                    return;
                }

                $new_filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $file_name);
                $upload_path = $upload_dir . $new_filename;

                if (move_uploaded_file($tmp_name, $upload_path)) {
                    $fichiers_sauvegardes[] = $new_filename;
                } else {
                    $this->vue->afficherFormulaire([
                        'error' => 'Erreur lors du téléchargement du fichier ' . $file_name,
                        'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
                    ]);
                    return;
                }
            }

            if (!empty($fichiers_sauvegardes)) {
                if ($this->modele->soumettreRendu($id_projet, $id_groupe, $fichiers_sauvegardes, $commentaire)) {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Rendu soumis avec succès'
                    ];
                    header('Location: index.php?module=rendu&action=list');
                    exit;
                }
            }

            $this->vue->afficherFormulaire([
                'error' => 'Erreur lors de la soumission du rendu',
                'projets' => $this->modele->getProjetsPourEtudiant($_SESSION['user']['id'])
            ]);
        } else {
            $projets = $this->modele->getProjetsPourEtudiant($_SESSION['user']['id']);
            $this->vue->afficherFormulaire(['projets' => $projets]);
        }
    }
}