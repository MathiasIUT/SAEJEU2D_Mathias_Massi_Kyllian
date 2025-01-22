<?php
require_once 'controleur_utilisateur.php';

class Module_utilisateur {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_utilisateur();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'create':
                $this->controleur->creerUtilisateur();
                break;
            case 'list':
                $this->controleur->listerUtilisateurs();
                break;
            case 'edit':
                $this->controleur->modifierUtilisateur();
                break;
            case 'delete':
                $this->controleur->supprimerUtilisateur();
                break;
            default:
                $this->controleur->listerUtilisateurs();
        }
    }
}