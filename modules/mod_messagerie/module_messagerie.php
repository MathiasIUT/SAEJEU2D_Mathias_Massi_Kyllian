<?php
require_once 'controleur_messagerie.php';

class Module_messagerie {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_messagerie();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'nouveau':
                $this->controleur->nouveauMessage();
                break;
            case 'lire':
                $this->controleur->lireMessage();
                break;
            case 'supprimer':
                $this->controleur->supprimerMessage();
                break;
            default:
                $this->controleur->afficherMessages();
        }
    }
}