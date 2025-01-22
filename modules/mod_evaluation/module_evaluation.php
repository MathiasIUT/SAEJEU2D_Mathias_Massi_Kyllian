<?php
require_once dirname(__FILE__) . '/controleur_evaluation.php';

class Module_evaluation {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_evaluation();
    }
    
    public function execute($action) {
        switch ($action) {
            case 'list':
                $this->controleur->listerEvaluations();
                break;
            case 'create':
                $this->controleur->creerEvaluation();
                break;
            case 'delegate':
                $this->controleur->deleguerEvaluation();
                break;
            case 'note':
                $this->controleur->noterEvaluation();
                break;
            default:
                header('Location: index.php?module=evaluation&action=list');
                exit;
        }
    }
}