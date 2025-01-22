<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_evaluation extends View {
    public function afficherFormulaire($data = []) {
        $this->render('evaluation/formulaire', $data);
    }
    
    public function afficherListe($data) {
        $this->render('evaluation/liste', $data);
    }
    
    public function afficherDelegation($data) {
        $this->render('evaluation/delegation', $data);
    }
    
    public function afficherNotation($data) {
        $this->render('evaluation/notation', $data);
    }
}