<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_dashboard extends Model {
    // Méthodes pour l'admin
    public function getTotalUtilisateurs() {
        $query = $this->db->query('SELECT COUNT(*) as total FROM utilisateurs');
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getTotalProjets() {
        $query = $this->db->query('SELECT COUNT(*) as total FROM projet');
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getTotalRendus() {
        $query = $this->db->query('SELECT COUNT(*) as total FROM rendu_projet');
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    public function getDerniersUtilisateurs() {
        $query = $this->db->query('SELECT * FROM utilisateurs ORDER BY id DESC LIMIT 5');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGroupesEtudiant($id_etudiant) {
        try {
            $query = $this->db->prepare('
                SELECT DISTINCT 
                    p.id_projet,
                    p.titre as projet_titre,
                    p.description as projet_description,
                    gp.id_groupe,
                    gp.titre as groupe_titre,
                    p.groupe_modifiable,
                    GROUP_CONCAT(CONCAT(u.prenom, " ", u.nom)) as membres
                FROM groupe_etudiant ge
                JOIN groupe_projet gp ON ge.id_groupe = gp.id_groupe
                JOIN projet p ON gp.id_projet = p.id_projet
                LEFT JOIN groupe_etudiant ge2 ON gp.id_groupe = ge2.id_groupe
                LEFT JOIN utilisateurs u ON ge2.id_etudiant = u.id
                WHERE ge.id_etudiant = ?
                GROUP BY p.id_projet, gp.id_groupe
                ORDER BY p.date_creation DESC
            ');
            $query->execute([$id_etudiant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des groupes: " . $e->getMessage());
            return [];
        }
    }
    
    public function getRessourcesEtudiant($id_etudiant) {
        try {
            $query = $this->db->prepare('
                SELECT DISTINCT 
                    r.*,
                    u.nom as auteur_nom,
                    u.prenom as auteur_prenom,
                    p.titre as projet_titre
                FROM ressource r
                LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id
                LEFT JOIN projet p ON r.id_projet = p.id_projet
                WHERE r.id_projet IN (
                    SELECT DISTINCT p2.id_projet
                    FROM groupe_etudiant ge
                    JOIN groupe_projet gp ON ge.id_groupe = gp.id_groupe
                    JOIN projet p2 ON gp.id_projet = p2.id_projet
                    WHERE ge.id_etudiant = ?
                )
                OR r.id_projet IS NULL
                ORDER BY r.mise_en_avant DESC, r.date_creation DESC
            ');
            $query->execute([$id_etudiant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des ressources: " . $e->getMessage());
            return [];
        }
    }
    
    public function getProjetsChampsEtudiant($id_etudiant) {
        try {
            $query = $this->db->prepare('
                SELECT pc.*, p.titre as projet_titre
                FROM projet_champ pc
                JOIN projet p ON pc.id_projet = p.id_projet
                JOIN groupe_projet gp ON p.id_projet = gp.id_projet
                JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
                WHERE ge.id_etudiant = ?
                AND pc.modifiable_etudiant = 1
                ORDER BY p.titre, pc.nom
            ');
            $query->execute([$id_etudiant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des champs: " . $e->getMessage());
            return [];
        }
    }
    
    // Méthodes pour l'enseignant
    public function getProjetsByEnseignant($id_enseignant) {
        $query = $this->db->prepare('SELECT * FROM projet WHERE id_responsable = ? ORDER BY id_projet DESC');
        $query->execute([$id_enseignant]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getDerniersRendusByEnseignant($id_enseignant) {
        $query = $this->db->prepare('
            SELECT r.*, p.titre as projet_titre, u.login as etudiant_login 
            FROM rendu_projet r 
            JOIN projet p ON r.id_projet = p.id_projet 
            JOIN groupe_projet gp ON p.id_projet = gp.id_projet
            JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
            JOIN utilisateurs u ON ge.id_etudiant = u.id
            WHERE p.id_responsable = ? 
            ORDER BY r.date_creation DESC 
            LIMIT 5
        ');
        $query->execute([$id_enseignant]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalEtudiants() {
        $query = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'etudiant'");
        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    // Méthodes pour l'étudiant
    public function getProjetsByEtudiant($id_etudiant) {
        $query = $this->db->prepare('
            SELECT DISTINCT p.* 
            FROM projet p 
            JOIN groupe_projet gp ON p.id_projet = gp.id_projet
            JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
            WHERE ge.id_etudiant = ? 
            ORDER BY p.id_projet DESC
        ');
        $query->execute([$id_etudiant]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRendusByEtudiant($id_etudiant) {
        $query = $this->db->prepare('
            SELECT r.*, p.titre as projet_titre 
            FROM rendu_projet r 
            JOIN projet p ON r.id_projet = p.id_projet 
            JOIN groupe_projet gp ON p.id_projet = gp.id_projet
            JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
            WHERE ge.id_etudiant = ? 
            ORDER BY r.date_creation DESC
        ');
        $query->execute([$id_etudiant]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProchainRendus($id_etudiant) {
        // Cette méthode pourrait être étendue avec une table de dates limites
        return [];
    }
}