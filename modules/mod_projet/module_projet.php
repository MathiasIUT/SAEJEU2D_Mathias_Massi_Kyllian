<?php
require_once dirname(__FILE__) . '/controleur_projet.php';

class Module_projet {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_projet();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'list':
                $this->controleur->listerProjets();
                break;
            case 'create':
                $this->controleur->creerProjet();
                break;
            case 'edit':
                $this->controleur->modifierProjet();
                break;
            case 'delete':
                $this->controleur->supprimerProjet();
                break;
            case 'details':
                $this->controleur->afficherDetails();
                break;
                case 'soutenances':
                    $this->controleur->gererSoutenances();
                    break;
                    case 'groupes':
                        $this->controleur->gererGroupes();
                        break;
            default:
                header('Location: index.php?module=projet&action=list');
                exit;
        }
    }
}
