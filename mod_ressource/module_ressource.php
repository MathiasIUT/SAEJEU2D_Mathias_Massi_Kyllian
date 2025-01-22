<?php
require_once 'controleur_ressource.php';

class Module_ressource {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_ressource();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'list':
                $this->controleur->listerRessources();
                break;
            case 'add':
                $this->controleur->ajouterRessource();
                break;
            default:
                header('Location: index.php');
                exit;
        }
    }
}