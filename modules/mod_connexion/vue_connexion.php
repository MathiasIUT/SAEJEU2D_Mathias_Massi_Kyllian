<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_connexion extends View {
    public function afficherFormulaire($data = []) {
        $this->render('connexion/formulaire', $data);
    }
    
    public function afficherRegister($data = []) {
        $this->render('connexion/register', $data);
    }
}