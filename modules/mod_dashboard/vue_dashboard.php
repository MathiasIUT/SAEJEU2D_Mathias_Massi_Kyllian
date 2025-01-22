<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_dashboard extends View {
    public function afficherDashboard($data) {
        switch ($_SESSION['user']['role']) {
            case 'admin':
                $this->render('dashboard/admin', $data);
                break;
            case 'enseignant':
                $this->render('dashboard/enseignant', $data);
                break;
            case 'etudiant':
                $this->render('dashboard/etudiant', $data);
                break;
            default:
                throw new Exception('Rôle non reconnu');
        }
    }
}