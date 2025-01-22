<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_soutenance extends View {
    public function afficherFormulaire($data = []) {
        $this->render('soutenance/formulaire', $data);
    }
    
    public function afficherListe($soutenances) {
        $this->render('soutenance/liste', ['soutenances' => $soutenances]);
    }
    
    public function afficherPlanification($data) {
        $this->render('soutenance/planification', $data);
    }
}