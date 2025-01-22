<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_champ extends View {
    public function afficherFormulaire($data = []) {
        $this->render('champ/formulaire', $data);
    }
    
    public function afficherListe($data = []) {
        $this->render('champ/liste', $data);
    }
}