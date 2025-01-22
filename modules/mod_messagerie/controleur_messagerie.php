<?php
require_once 'modele_messagerie.php';
require_once 'vue_messagerie.php';

class Controleur_messagerie {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_messagerie();
        $this->vue = new Vue_messagerie();
    }
    
    public function afficherMessages() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vous devez être connecté pour accéder à la messagerie.'];
            header('Location: index.php?module=connexion');
            exit;
        }
        
        // Vérifiez les rôles si nécessaire
        if (!in_array($_SESSION['user']['role'], ['etudiant', 'enseignant', 'admin'])) {
            http_response_code(403);
            echo "403 Accès refusé. Vous n'avez pas les permissions nécessaires pour accéder à cette page.";
            exit;
        }
        
        $messages = $this->modele->getMessages($_SESSION['user']['id']);
        $this->vue->afficherMessages($messages);
    }
    
    
    public function nouveauMessage() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Vous devez être connecté pour envoyer un message.'];
            header('Location: index.php?module=connexion');
            exit;
        }
    
        // Rôles autorisés (optionnel)
        if (!in_array($_SESSION['user']['role'], ['etudiant', 'enseignant', 'admin'])) {
            http_response_code(403);
            echo "403 Accès refusé. Vous n'avez pas les permissions nécessaires pour envoyer un message.";
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $destinataire = $_POST['destinataire'] ?? '';
            $sujet = $_POST['sujet'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
    
            if ($this->modele->envoyerMessage($_SESSION['user']['id'], $destinataire, $sujet, $contenu)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Message envoyé avec succès'];
                header('Location: index.php?module=messagerie');
                exit;
            } else {
                $utilisateurs = $this->modele->getUtilisateursDisponibles();
                $this->vue->afficherFormulaire(['error' => 'Erreur lors de l\'envoi du message', 'utilisateurs' => $utilisateurs]);
            }
        } else {
            $utilisateurs = $this->modele->getUtilisateursDisponibles();
            $this->vue->afficherFormulaire(['utilisateurs' => $utilisateurs]);
        }
    }
    
    
    public function lireMessage() {
        $id_message = $_GET['id'] ?? 0;
        $message = $this->modele->getMessageById($id_message);
        
        if ($message && ($message['id_destinataire'] == $_SESSION['user']['id'] || $message['id_expediteur'] == $_SESSION['user']['id'])) {
            if ($message['id_destinataire'] == $_SESSION['user']['id'] && !$message['lu']) {
                $this->modele->marquerCommeLu($id_message);
            }
            $this->vue->afficherMessage($message);
        } else {
            header('Location: index.php?module=messagerie');
        }
    }
    
    public function supprimerMessage() {
        $id_message = $_GET['id'] ?? 0;
        if ($this->modele->supprimerMessage($id_message, $_SESSION['user']['id'])) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Message supprimé avec succès'];
        }
        header('Location: index.php?module=messagerie');
    }
}