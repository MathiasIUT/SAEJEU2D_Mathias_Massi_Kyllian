<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_champ extends Model {
    public function creerChamp($id_projet, $nom, $description, $type, $obligatoire, $modifiable_groupe, $ordre) {
        try {
            $query = $this->db->prepare('
                INSERT INTO champ_projet (id_projet, nom, description, type, obligatoire, modifiable_groupe, ordre)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            return $query->execute([
                $id_projet, $nom, $description, $type, 
                $obligatoire ? 1 : 0, 
                $modifiable_groupe ? 1 : 0, 
                $ordre
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création du champ: " . $e->getMessage());
            return false;
        }
    }
    
    public function getChampById($id_champ) {
        $query = $this->db->prepare('
            SELECT * FROM champ_projet WHERE id_champ = ?
        ');
        $query->execute([$id_champ]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getChampsByProjet($id_projet) {
        $query = $this->db->prepare('
            SELECT * FROM champ_projet 
            WHERE id_projet = ?
            ORDER BY ordre ASC
        ');
        $query->execute([$id_projet]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function modifierChamp($id_champ, $nom, $description, $type, $obligatoire, $modifiable_groupe, $ordre) {
        try {
            $query = $this->db->prepare('
                UPDATE champ_projet 
                SET nom = ?, description = ?, type = ?, 
                    obligatoire = ?, modifiable_groupe = ?, ordre = ?
                WHERE id_champ = ?
            ');
            return $query->execute([
                $nom, $description, $type, 
                $obligatoire ? 1 : 0, 
                $modifiable_groupe ? 1 : 0, 
                $ordre, 
                $id_champ
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification du champ: " . $e->getMessage());
            return false;
        }
    }
    
    public function supprimerChamp($id_champ) {
        try {
            $query = $this->db->prepare('DELETE FROM champ_projet WHERE id_champ = ?');
            return $query->execute([$id_champ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du champ: " . $e->getMessage());
            return false;
        }
    }
    
    public function estResponsableProjet($id_utilisateur, $id_projet) {
        $query = $this->db->prepare('
            SELECT COUNT(*) as count
            FROM projet p
            LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
            WHERE p.id_projet = ?
            AND (
                p.id_responsable = ?
                OR (pr.id_enseignant = ? AND pr.role IN ("responsable", "co-responsable"))
                OR ? IN (SELECT id FROM utilisateurs WHERE role = "admin")
            )
        ');
        $query->execute([$id_projet, $id_utilisateur, $id_utilisateur, $id_utilisateur]);
        return $query->fetch()['count'] > 0;
    }
}