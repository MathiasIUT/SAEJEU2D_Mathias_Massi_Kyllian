<?php
require_once dirname(__FILE__) . '/controleur_rendu.php';

class Module_rendu {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_rendu();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'submit':
                $this->controleur->soumettreRendu();
                break;
            case 'list':
                $this->controleur->listerRendus();
                break;
            case 'evaluate':
                $this->controleur->evaluerRendu();
                break;
            case 'download':
                $this->controleur->telechargerRendu();
                break;
            default:
                header('Location: index.php');
                exit;
        }
    }
}
