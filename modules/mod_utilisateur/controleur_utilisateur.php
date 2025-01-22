<?php
require_once 'modele_utilisateur.php';
require_once 'vue_utilisateur.php';

class Controleur_utilisateur {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_utilisateur();
        $this->vue = new Vue_utilisateur();
    }
    
    public function creerUtilisateur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            
            if ($this->modele->creerUtilisateur($login, $password, $role)) {
                header('Location: index.php?module=utilisateur&action=list');
            } else {
                $this->vue->afficherFormulaire(['error' => 'Erreur lors de la création de l\'utilisateur']);
            }
        } else {
            $this->vue->afficherFormulaire();
        }
    }
    
    public function listerUtilisateurs() {
        $utilisateurs = $this->modele->getUtilisateurs();
        $this->vue->afficherListe($utilisateurs);
    }
    
    public function modifierUtilisateur() {
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            
            if ($this->modele->modifierUtilisateur($id, $login, $password, $role)) {
                header('Location: index.php?module=utilisateur&action=list');
            } else {
                $utilisateur = $this->modele->getUtilisateurById($id);
                $this->vue->afficherFormulaire(['error' => 'Erreur lors de la modification', 'utilisateur' => $utilisateur]);
            }
        } else {
            $utilisateur = $this->modele->getUtilisateurById($id);
            $this->vue->afficherFormulaire(['utilisateur' => $utilisateur]);
        }
    }
    
    public function supprimerUtilisateur() {
        $id = $_GET['id'] ?? 0;
        if ($this->modele->supprimerUtilisateur($id)) {
            header('Location: index.php?module=utilisateur&action=list');
        }
    }
}