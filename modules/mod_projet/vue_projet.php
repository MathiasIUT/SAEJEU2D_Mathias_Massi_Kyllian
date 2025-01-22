<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_projet extends View {
    public function afficherFormulaire($data = []) {
        $this->render('projet/formulaire', $data);
    }
    
    public function afficherListe($data) {
        $this->render('projet/liste', $data);
    }
    
    public function afficherDetails($data) {
        $this->render('projet/details', $data);
    }
    
    
    public function afficherGroupes($data) {
        $this->render('projet/groupes', $data);
    }
    
    public function afficherChamps($data) {
        $this->render('projet/champs', $data);
    }
    
    public function afficherSoutenances($data) {
        $this->render('projet/soutenances', $data);
    }
}