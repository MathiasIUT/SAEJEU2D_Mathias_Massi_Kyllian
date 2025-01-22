<?php
require_once 'modele_dashboard.php';
require_once 'vue_dashboard.php';

class Controleur_dashboard {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_dashboard();
        $this->vue = new Vue_dashboard();
    }
    
    public function afficherDashboard() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['flash'] = [
                'type' => 'danger',
                'message' => 'Vous devez être connecté pour accéder au tableau de bord'
            ];
            header('Location: index.php?module=connexion');
            exit;
        }
    
        $data = [];
        $id_utilisateur = $_SESSION['user']['id'];
    
        switch ($_SESSION['user']['role']) {
            case 'admin':
                $data['total_utilisateurs'] = $this->modele->getTotalUtilisateurs();
                $data['total_projets'] = $this->modele->getTotalProjets();
                $data['total_rendus'] = $this->modele->getTotalRendus();
                $data['derniers_utilisateurs'] = $this->modele->getDerniersUtilisateurs();
                break;
    
            case 'enseignant':
                $data['mes_projets'] = $this->modele->getProjetsByEnseignant($id_utilisateur);
                $data['derniers_rendus'] = $this->modele->getDerniersRendusByEnseignant($id_utilisateur);
                $data['total_etudiants'] = $this->modele->getTotalEtudiants();
                break;
    
            case 'etudiant':
                $data['mes_groupes'] = $this->modele->getGroupesEtudiant($id_utilisateur);
                $data['mes_ressources'] = $this->modele->getRessourcesEtudiant($id_utilisateur);
                $data['mes_champs'] = $this->modele->getProjetsChampsEtudiant($id_utilisateur);
                $data['mes_rendus'] = $this->modele->getRendusByEtudiant($id_utilisateur);
                $data['prochains_rendus'] = $this->modele->getProchainRendus($id_utilisateur);
                break;
        }
    
        $this->vue->afficherDashboard($data);
    }
}