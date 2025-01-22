<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_groupe extends Model {
    public function creerGroupe($id_projet, $titre, $titre_modifiable, $image_modifiable) {
        try {
            $query = $this->db->prepare('
                INSERT INTO groupe_projet (id_projet, titre, titre_modifiable, image_modifiable)
                VALUES (?, ?, ?, ?)
            ');
            return $query->execute([
                $id_projet, $titre, 
                $titre_modifiable ? 1 : 0, 
                $image_modifiable ? 1 : 0
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création du groupe: " . $e->getMessage());
            return false;
        }
    }
    
    public function getGroupeById($id_groupe) {
        $query = $this->db->prepare('
            SELECT g.*, p.id_projet, p.titre as projet_titre,
                   COUNT(ge.id_etudiant) as nb_etudiants,
                   GROUP_CONCAT(CONCAT(u.prenom, " ", u.nom)) as membres
            FROM groupe_projet g
            JOIN projet p ON g.id_projet = p.id_projet
            LEFT JOIN groupe_etudiant ge ON g.id_groupe = ge.id_groupe
            LEFT JOIN utilisateurs u ON ge.id_etudiant = u.id
            WHERE g.id_groupe = ?
            GROUP BY g.id_groupe
        ');
        $query->execute([$id_groupe]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getGroupesByProjet($id_projet) {
        $query = $this->db->prepare('
            SELECT g.*, 
                   COUNT(ge.id_etudiant) as nb_etudiants,
                   GROUP_CONCAT(CONCAT(u.prenom, " ", u.nom)) as membres
            FROM groupe_projet g
            LEFT JOIN groupe_etudiant ge ON g.id_groupe = ge.id_groupe
            LEFT JOIN utilisateurs u ON ge.id_etudiant = u.id
            WHERE g.id_projet = ?
            GROUP BY g.id_groupe
            ORDER BY g.titre
        ');
        $query->execute([$id_projet]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function modifierGroupe($id_groupe, $titre, $titre_modifiable, $image_modifiable) {
        try {
            $query = $this->db->prepare('
                UPDATE groupe_projet 
                SET titre = ?, titre_modifiable = ?, image_modifiable = ?
                WHERE id_groupe = ?
            ');
            return $query->execute([
                $titre, 
                $titre_modifiable ? 1 : 0, 
                $image_modifiable ? 1 : 0,
                $id_groupe
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification du groupe: " . $e->getMessage());
            return false;
        }
    }
    
    public function ajouterEtudiant($id_groupe, $id_etudiant) {
        try {
            // Vérifier si l'étudiant n'est pas déjà dans un groupe pour ce projet
            $query = $this->db->prepare('
                SELECT COUNT(*) as count
                FROM groupe_etudiant ge
                JOIN groupe_projet gp ON ge.id_groupe = gp.id_groupe
                WHERE gp.id_projet = (
                    SELECT id_projet FROM groupe_projet WHERE id_groupe = ?
                )
                AND ge.id_etudiant = ?
            ');
            $query->execute([$id_groupe, $id_etudiant]);
            if ($query->fetch()['count'] > 0) {
                return false;
            }
            
            $query = $this->db->prepare('
                INSERT INTO groupe_etudiant (id_groupe, id_etudiant)
                VALUES (?, ?)
            ');
            return $query->execute([$id_groupe, $id_etudiant]);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de l'étudiant: " . $e->getMessage());
            return false;
        }
    }
    
    public function retirerEtudiant($id_groupe, $id_etudiant) {
        try {
            $query = $this->db->prepare('
                DELETE FROM groupe_etudiant 
                WHERE id_groupe = ? AND id_etudiant = ?
            ');
            return $query->execute([$id_groupe, $id_etudiant]);
        } catch (PDOException $e) {
            error_log("Erreur lors du retrait de l'étudiant: " . $e->getMessage());
            return false;
        }
    }
    
    public function getEtudiantsDisponibles($id_projet) {
        $query = $this->db->prepare('
            SELECT u.*
            FROM utilisateurs u
            WHERE u.role = "etudiant"
            AND u.id NOT IN (
                SELECT ge.id_etudiant
                FROM groupe_etudiant ge
                JOIN groupe_projet gp ON ge.id_groupe = gp.id_groupe
                WHERE gp.id_projet = ?
            )
            ORDER BY u.nom, u.prenom
        ');
        $query->execute([$id_projet]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function peutModifierGroupe($id_groupe, $id_utilisateur) {
        $query = $this->db->prepare('
            SELECT COUNT(*) as count
            FROM groupe_projet g
            JOIN projet p ON g.id_projet = p.id_projet
            LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
            WHERE g.id_groupe = ?
            AND (
                p.id_responsable = ?
                OR pr.id_enseignant = ?
                OR ? IN (SELECT id FROM utilisateurs WHERE role = "admin")
            )
        ');
        $query->execute([$id_groupe, $id_utilisateur, $id_utilisateur, $id_utilisateur]);
        return $query->fetch()['count'] > 0;
    }
    
    public function estResponsableProjet($id_projet, $id_utilisateur) {
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
    
    public function getProjetsPourEnseignant($id_utilisateur) {
        $query = $this->db->prepare('
            SELECT DISTINCT p.*
            FROM projet p
            LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
            WHERE p.id_responsable = ?
               OR pr.id_enseignant = ?
               OR ? IN (SELECT id FROM utilisateurs WHERE role = "admin")
            ORDER BY p.date_creation DESC
        ');
        $query->execute([$id_utilisateur, $id_utilisateur, $id_utilisateur]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}