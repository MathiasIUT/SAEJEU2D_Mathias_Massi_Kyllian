<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_ressource extends View {
    public function afficherFormulaire($data = []) {
        $this->render('ressource/formulaire', $data);
    }
    
    public function afficherListe($data) {
        $this->render('ressource/liste', $data);
    }
}