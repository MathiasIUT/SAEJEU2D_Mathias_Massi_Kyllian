<?php
require_once 'controleur_dashboard.php';

class Module_dashboard {
    private $controleur;
    
    public function __construct() {
        $this->controleur = new Controleur_dashboard();
    }
    
    public function execute($action) {
        switch ($action) {
            default:
                $this->controleur->afficherDashboard();
        }
    }
}