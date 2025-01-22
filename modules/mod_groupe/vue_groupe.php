<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_groupe extends View {
    public function afficherFormulaire($data = []) {
        $this->render('groupe/formulaire', $data);
    }
    
    public function afficherListe($data = []) {
        $this->render('groupe/liste', $data);
    }
}