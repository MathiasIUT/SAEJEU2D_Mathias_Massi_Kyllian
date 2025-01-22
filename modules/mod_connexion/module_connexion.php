<?php
require_once 'controleur_connexion.php';

class Module_connexion {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_connexion();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'login':
                $this->controleur->login();
                break;
            case 'register':
                $this->controleur->register();
                break;
            case 'logout':
                $this->controleur->logout();
                break;
            default:
                $this->controleur->index();
        }
    }
}