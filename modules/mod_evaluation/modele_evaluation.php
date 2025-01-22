<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_evaluation extends Model {
    public function creerEvaluation($id_projet, $titre, $description, $coefficient, $type, $id_rendu = null) {
        try {
            $query = $this->db->prepare('
                INSERT INTO evaluation (
                    id_projet, 
                    titre, 
                    description, 
                    coefficient, 
                    type, 
                    id_rendu,
                    id_evaluateur
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');

            $result = $query->execute([
                $id_projet, 
                $titre, 
                $description, 
                $coefficient, 
                $type, 
                $id_rendu,
                $_SESSION['user']['id']
            ]);

            if (!$result) {
                $error = $query->errorInfo();
                error_log("Erreur lors de l'insertion de l'évaluation: " . print_r($error, true));
                return false;
            }

            return true;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'évaluation: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }
    
    public function deleguerEvaluation($id_evaluation, $id_enseignant) {
        try {
            $query = $this->db->prepare('
                UPDATE evaluation 
                SET id_evaluateur_delegue = ? 
                WHERE id_evaluation = ?
                AND (id_evaluateur = ? OR EXISTS (
                    SELECT 1 FROM projet p
                    WHERE p.id_projet = evaluation.id_projet
                    AND p.id_responsable = ?
                ))
            ');
            return $query->execute([
                $id_enseignant, 
                $id_evaluation,
                $_SESSION['user']['id'],
                $_SESSION['user']['id']
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la délégation de l'évaluation: " . $e->getMessage());
            return false;
        }
    }
    
    public function noterEvaluation($id_evaluation, $id_etudiant, $note, $commentaire = '') {
        try {
            $query = $this->db->prepare('
                INSERT INTO evaluation_note (
                    id_evaluation,
                    id_etudiant,
                    note,
                    commentaire
                ) VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    note = VALUES(note),
                    commentaire = VALUES(commentaire)
            ');
            return $query->execute([
                $id_evaluation,
                $id_etudiant,
                $note,
                $commentaire
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la notation: " . $e->getMessage());
            return false;
        }
    }
    
    public function getEvaluationsEnseignant($id_enseignant) {
        try {
            $query = $this->db->prepare('
                SELECT e.*, p.titre as projet_titre,
                       u1.nom as evaluateur_nom, u1.prenom as evaluateur_prenom,
                       u2.nom as delegue_nom, u2.prenom as delegue_prenom
                FROM evaluation e
                JOIN projet p ON e.id_projet = p.id_projet
                LEFT JOIN utilisateurs u1 ON e.id_evaluateur = u1.id
                LEFT JOIN utilisateurs u2 ON e.id_evaluateur_delegue = u2.id
                WHERE e.id_evaluateur = ? 
                OR e.id_evaluateur_delegue = ?
                ORDER BY e.date_creation DESC
            ');
            $query->execute([$id_enseignant, $id_enseignant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des évaluations: " . $e->getMessage());
            return [];
        }
    }
    
    public function getEvaluationsEtudiant($id_etudiant) {
        try {
            $query = $this->db->prepare('
                SELECT e.*, p.titre as projet_titre,
                       en.note, en.commentaire
                FROM evaluation e
                JOIN projet p ON e.id_projet = p.id_projet
                LEFT JOIN evaluation_note en ON e.id_evaluation = en.id_evaluation 
                    AND en.id_etudiant = ?
                WHERE EXISTS (
                    SELECT 1 FROM groupe_etudiant ge
                    JOIN groupe_projet gp ON ge.id_groupe = gp.id_groupe
                    WHERE gp.id_projet = e.id_projet
                    AND ge.id_etudiant = ?
                )
                ORDER BY e.date_creation DESC
            ');
            $query->execute([$id_etudiant, $id_etudiant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des évaluations: " . $e->getMessage());
            return [];
        }
    }
    
    public function getEvaluationById($id_evaluation) {
        try {
            $query = $this->db->prepare('
                SELECT e.*, p.titre as projet_titre,
                       u1.nom as evaluateur_nom, u1.prenom as evaluateur_prenom,
                       u2.nom as delegue_nom, u2.prenom as delegue_prenom
                FROM evaluation e
                JOIN projet p ON e.id_projet = p.id_projet
                LEFT JOIN utilisateurs u1 ON e.id_evaluateur = u1.id
                LEFT JOIN utilisateurs u2 ON e.id_evaluateur_delegue = u2.id
                WHERE e.id_evaluation = ?
            ');
            $query->execute([$id_evaluation]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de l'évaluation: " . $e->getMessage());
            return null;
        }
    }


    
    public function getProjetsEnseignant($id_enseignant) {
        try {
            $query = $this->db->prepare('
                SELECT DISTINCT p.*
                FROM projet p
                LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
                WHERE p.id_responsable = ? 
                OR pr.id_enseignant = ?
                ORDER BY p.date_creation DESC
            ');
            $query->execute([$id_enseignant, $id_enseignant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des projets: " . $e->getMessage());
            return [];
        }
    }
    
    public function getEnseignantsDisponibles($id_evaluation) {
        try {
            $query = $this->db->prepare('
                SELECT u.*
                FROM utilisateurs u
                WHERE u.role = "enseignant"
                AND u.id != ?
                ORDER BY u.nom, u.prenom
            ');
            $query->execute([$_SESSION['user']['id']]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des enseignants: " . $e->getMessage());
            return [];
        }
    }
}