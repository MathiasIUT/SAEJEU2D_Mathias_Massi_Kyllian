<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_rendu extends Model {
    public function getRendus() {
        try {
            $query = $this->db->prepare('
                SELECT 
                    rp.*,
                    p.titre as projet_titre,
                    gp.titre as groupe_titre,
                    GROUP_CONCAT(DISTINCT CONCAT(u.prenom, " ", u.nom)) as etudiants,
                    GROUP_CONCAT(DISTINCT u.login) as etudiant_login
                FROM rendu_projet rp
                LEFT JOIN projet p ON rp.id_projet = p.id_projet
                LEFT JOIN groupe_projet gp ON rp.id_groupe = gp.id_groupe
                LEFT JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
                LEFT JOIN utilisateurs u ON ge.id_etudiant = u.id
                GROUP BY rp.id_rendu, rp.date_creation, rp.note, rp.commentaire, p.titre, gp.titre
                ORDER BY rp.date_creation DESC
            ');
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des rendus: " . $e->getMessage());
            return [];
        }
    }
    
    public function getRendusEtudiant($id_etudiant) {
        try {
            $query = $this->db->prepare('
                SELECT 
                    rp.*,
                    p.titre as projet_titre,
                    gp.titre as groupe_titre,
                    GROUP_CONCAT(DISTINCT CONCAT(u.prenom, " ", u.nom)) as etudiants,
                    GROUP_CONCAT(DISTINCT u.login) as etudiant_login
                FROM rendu_projet rp
                LEFT JOIN projet p ON rp.id_projet = p.id_projet
                LEFT JOIN groupe_projet gp ON rp.id_groupe = gp.id_groupe
                LEFT JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
                LEFT JOIN utilisateurs u ON ge.id_etudiant = u.id
                WHERE ge.id_etudiant = :id_etudiant
                GROUP BY rp.id_rendu, rp.date_creation, rp.note, rp.commentaire, p.titre, gp.titre
                ORDER BY rp.date_creation DESC
            ');
            $query->execute([':id_etudiant' => $id_etudiant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des rendus de l'étudiant: " . $e->getMessage());
            return [];
        }
    }
    
    public function getRenduById($id_rendu) {
        try {
            $query = $this->db->prepare('
                SELECT 
                    rp.*,
                    p.titre as projet_titre,
                    gp.titre as groupe_titre,
                    GROUP_CONCAT(DISTINCT CONCAT(u.prenom, " ", u.nom)) as etudiants,
                    GROUP_CONCAT(DISTINCT u.login) as etudiant_login
                FROM rendu_projet rp
                LEFT JOIN projet p ON rp.id_projet = p.id_projet
                LEFT JOIN groupe_projet gp ON rp.id_groupe = gp.id_groupe
                LEFT JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
                LEFT JOIN utilisateurs u ON ge.id_etudiant = u.id
                WHERE rp.id_rendu = :id_rendu
                GROUP BY rp.id_rendu, rp.date_creation, rp.note, rp.commentaire, p.titre, gp.titre
            ');
            $query->execute([':id_rendu' => $id_rendu]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du rendu: " . $e->getMessage());
            return null;
        }
    }

  



    
    public function getGroupeEtudiant($id_etudiant, $id_projet) {
        $query = $this->db->prepare('
            SELECT gp.id_groupe
            FROM groupe_projet gp
            JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
            WHERE ge.id_etudiant = ? AND gp.id_projet = ?
        ');
        $query->execute([$id_etudiant, $id_projet]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id_groupe'] : null;
    }
    
    public function soumettreRendu($id_projet, $id_groupe, $fichiers, $commentaire = '') {
        $query = $this->db->prepare('
            INSERT INTO rendu_projet (id_projet, id_groupe, fichiers, commentaire_etudiant) 
            VALUES (?, ?, ?, ?)
        ');
        return $query->execute([
            $id_projet,
            $id_groupe,
            json_encode($fichiers),
            $commentaire
        ]);
    }
    
    public function evaluerRendu($id_rendu, $note, $commentaire) {
        $query = $this->db->prepare('
            UPDATE rendu_projet 
            SET note = ?, 
                commentaire = ?,
                date_evaluation = CURRENT_TIMESTAMP 
            WHERE id_rendu = ?
        ');
        return $query->execute([$note, $commentaire, $id_rendu]);
    }
    
    public function getProjetsPourEtudiant($id_etudiant) {
        $query = $this->db->prepare('
            SELECT DISTINCT p.*
            FROM projet p
            JOIN groupe_projet gp ON p.id_projet = gp.id_projet
            JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
            WHERE ge.id_etudiant = ?
            ORDER BY p.date_creation DESC
        ');
        $query->execute([$id_etudiant]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}