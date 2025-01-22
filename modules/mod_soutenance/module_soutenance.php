<?php
require_once 'controleur_soutenance.php';

class Module_soutenance {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_soutenance();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'create':
                $this->controleur->creerSoutenance();
                break;
            case 'list':
                $this->controleur->listerSoutenances();
                break;
            case 'planifier':
                $this->controleur->planifierPassage();
                break;
            case 'supprimer':
                $this->controleur->supprimerSoutenance();
                break;
            default:
                $this->controleur->listerSoutenances();
        }
    }
}