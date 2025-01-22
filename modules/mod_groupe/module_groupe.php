<?php
require_once 'controleur_groupe.php';

class Module_groupe {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_groupe();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'create':
                $this->controleur->creerGroupe();
                break;
            case 'edit':
                $this->controleur->modifierGroupe();
                break;
            case 'delete':
                $this->controleur->supprimerGroupe();
                break;
            case 'add_student':
                $this->controleur->ajouterEtudiant();
                break;
            case 'remove_student':
                $this->controleur->retirerEtudiant();
                break;
            case 'list':
                $this->controleur->listerGroupes();
                break;
            default:
                $this->controleur->listerGroupes();
        }
    }
}