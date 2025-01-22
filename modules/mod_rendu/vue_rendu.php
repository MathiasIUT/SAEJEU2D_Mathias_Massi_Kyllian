<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_rendu extends View {
    public function afficherFormulaire($data = []) {
        $this->render('rendu/formulaire', $data);
    }
    
    public function afficherListe($data) {
        if (!isset($data['rendus'])) {
            $data['rendus'] = [];
        }
        $this->render('rendu/liste', $data);
    }
    
    public function afficherEvaluation($data) {
        $this->render('rendu/evaluation', $data);
    }
}
