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
            case 'edit':
                $this->controleur->modifierRessource();
                break;
            case 'delete':
                $this->controleur->supprimerRessource();
                break;
            case 'toggle-featured':
                $this->controleur->toggleMiseEnAvant();
                break;
            default:
                header('Location: index.php?module=ressource&action=list');
                exit;
        }
    }
}