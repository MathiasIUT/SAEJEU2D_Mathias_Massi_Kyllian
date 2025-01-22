<?php
require_once 'controleur_champ.php';

class Module_champ {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_champ();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'create':
                $this->controleur->creerChamp();
                break;
            case 'edit':
                $this->controleur->modifierChamp();
                break;
            case 'delete':
                $this->controleur->supprimerChamp();
                break;
            case 'list':
                $this->controleur->listerChamps();
                break;
            default:
                $this->controleur->listerChamps();
        }
    }
}