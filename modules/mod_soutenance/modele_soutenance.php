<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_soutenance extends Model {
    public function getSoutenanceById($id_soutenance) {
        $query = $this->db->prepare('
            SELECT s.*, p.titre as projet_titre
            FROM soutenance s
            JOIN projet p ON s.id_projet = p.id_projet
            WHERE s.id_soutenance = ?
        ');
        $query->execute([$id_soutenance]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getGroupesDisponibles($id_soutenance) {
        $query = $this->db->prepare('
            SELECT gp.*
            FROM groupe_projet gp
            JOIN soutenance s ON gp.id_projet = s.id_projet
            WHERE s.id_soutenance = ?
            AND NOT EXISTS (
                SELECT 1 FROM soutenance_groupe sg
                WHERE sg.id_soutenance = s.id_soutenance
                AND sg.id_groupe = gp.id_groupe
            )
            ORDER BY gp.titre
        ');
        $query->execute([$id_soutenance]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function planifierPassage($id_soutenance, $id_groupe, $heure_passage) {
        try {
            // Vérifier si le créneau est disponible
            $query = $this->db->prepare('
                SELECT COUNT(*) as nb
                FROM soutenance_groupe
                WHERE id_soutenance = ? AND heure_passage = ?
            ');
            $query->execute([$id_soutenance, $heure_passage]);
            if ($query->fetch()['nb'] > 0) {
                return false;
            }
            
            // Ajouter le passage
            $query = $this->db->prepare('
                INSERT INTO soutenance_groupe (id_soutenance, id_groupe, heure_passage)
                VALUES (?, ?, ?)
            ');
            return $query->execute([$id_soutenance, $id_groupe, $heure_passage]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la planification du passage: " . $e->getMessage());
            return false;
        }
    }
    
    public function peutModifierSoutenance($id_soutenance, $id_utilisateur) {
        $query = $this->db->prepare('
            SELECT COUNT(*) as count
            FROM soutenance s
            JOIN projet p ON s.id_projet = p.id_projet
            LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
            WHERE s.id_soutenance = ?
            AND (
                p.id_responsable = ?
                OR pr.id_enseignant = ?
                OR ? IN (SELECT id FROM utilisateurs WHERE role = "admin")
            )
        ');
        $query->execute([$id_soutenance, $id_utilisateur, $id_utilisateur, $id_utilisateur]);
        return $query->fetch()['count'] > 0;
    }
    
    public function supprimerSoutenance($id_soutenance) {
        try {
            $this->db->beginTransaction();
            
            // Supprimer d'abord les passages planifiés
            $query = $this->db->prepare('DELETE FROM soutenance_groupe WHERE id_soutenance = ?');
            $query->execute([$id_soutenance]);
            
            // Puis supprimer la soutenance
            $query = $this->db->prepare('DELETE FROM soutenance WHERE id_soutenance = ?');
            $success = $query->execute([$id_soutenance]);
            
            $this->db->commit();
            return $success;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression de la soutenance: " . $e->getMessage());
            return false;
        }
    }
}