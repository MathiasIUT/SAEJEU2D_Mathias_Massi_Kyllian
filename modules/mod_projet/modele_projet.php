<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_projet extends Model {
    public function creerSoutenance($id_projet, $date_soutenance, $salle, $duree) {
        $query = $this->db->prepare('
            INSERT INTO soutenance (id_projet, date_soutenance, salle, duree)
            VALUES (?, ?, ?, ?)
        ');
        return $query->execute([$id_projet, $date_soutenance, $salle, $duree]);
    }
   
    public function ajouterPassage($id_soutenance, $id_groupe, $heure_passage) {
        $query = $this->db->prepare('
            INSERT INTO soutenance_passage (id_soutenance, id_groupe, heure_passage)
            VALUES (?, ?, ?)
        ');
        return $query->execute([$id_soutenance, $id_groupe, $heure_passage]);
    }

    public function evaluerPassage($id_passage, $note, $commentaire) {
        $query = $this->db->prepare('
            UPDATE soutenance_passage 
            SET note = ?, commentaire = ?, date_evaluation = CURRENT_TIMESTAMP
            WHERE id_passage = ?
        ');
        return $query->execute([$note, $commentaire, $id_passage]);
    }

    public function getSoutenances($id_projet) {
        $query = $this->db->prepare('
            SELECT s.*, 
                   sp.id_passage,
                   sp.heure_passage,
                   sp.note,
                   sp.commentaire,
                   g.titre as groupe_titre
            FROM soutenance s
            LEFT JOIN soutenance_passage sp ON s.id_soutenance = sp.id_soutenance
            LEFT JOIN groupe_projet g ON sp.id_groupe = g.id_groupe
            WHERE s.id_projet = ?
            ORDER BY s.date_soutenance, sp.heure_passage
        ');
        $query->execute([$id_projet]);
        
        $soutenances = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $id_soutenance = $row['id_soutenance'];
            if (!isset($soutenances[$id_soutenance])) {
                $soutenances[$id_soutenance] = [
                    'id_soutenance' => $id_soutenance,
                    'date_soutenance' => $row['date_soutenance'],
                    'salle' => $row['salle'],
                    'duree' => $row['duree'],
                    'passages' => []
                ];
            }
            if ($row['id_passage']) {
                $soutenances[$id_soutenance]['passages'][] = [
                    'id_passage' => $row['id_passage'],
                    'heure_passage' => $row['heure_passage'],
                    'note' => $row['note'],
                    'commentaire' => $row['commentaire'],
                    'groupe_titre' => $row['groupe_titre']
                ];
            }
        }
        return array_values($soutenances);
    }

    public function supprimerSoutenance($id_soutenance) {
        $query = $this->db->prepare('DELETE FROM soutenance WHERE id_soutenance = ?');
        return $query->execute([$id_soutenance]);
    }

    public function getEvaluationsProjet($id_projet) {
        try {
            $query = $this->db->prepare('
                SELECT e.*,
                       CONCAT(u.prenom, " ", u.nom) as evaluateur,
                       CONCAT(u2.prenom, " ", u2.nom) as delegue
                FROM evaluation e
                LEFT JOIN utilisateurs u ON e.id_evaluateur = u.id
                LEFT JOIN utilisateurs u2 ON e.id_evaluateur_delegue = u2.id
                WHERE e.id_projet = ?
                ORDER BY e.date_evaluation DESC
            ');
            $query->execute([$id_projet]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des évaluations: " . $e->getMessage());
            return [];
        }
    }

    public function utilisateurExiste($id_utilisateur) {
        try {
            if (!$id_utilisateur) {
                error_log("ID utilisateur non défini");
                return false;
            }
    
            $query = $this->db->prepare('
                SELECT COUNT(*) as count 
                FROM utilisateurs 
                WHERE id = :id 
                AND role IN ("enseignant", "admin")
            ');
            
            $query->execute([':id' => $id_utilisateur]);
            $result = $query->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }

    public function creerProjet($titre, $description, $annee, $semestre, $travail_groupe, $groupe_modifiable, $id_responsable, $co_responsables = [], $intervenants = []) {
        try {
            $this->db->beginTransaction();
            
            $query = $this->db->prepare('
                INSERT INTO projet (
                    titre, 
                    description, 
                    annee,
                    semestre, 
                    travail_groupe,
                    groupe_modifiable,
                    id_responsable,
                    date_creation
                ) VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ');
            
            $success = $query->execute([
                $titre, 
                $description, 
                $annee,
                $semestre, 
                $travail_groupe ? 1 : 0,
                $groupe_modifiable ? 1 : 0,
                $id_responsable
            ]);
            
            if (!$success) {
                throw new Exception("Erreur lors de la création du projet");
            }
            
            $id_projet = $this->db->lastInsertId();
            
            foreach ($co_responsables as $id_enseignant) {
                if ($id_enseignant != $id_responsable) {
                    $query = $this->db->prepare('
                        INSERT INTO projet_responsable (id_projet, id_enseignant, role) 
                        VALUES (?, ?, "co-responsable")
                    ');
                    $query->execute([$id_projet, $id_enseignant]);
                }
            }
            
            foreach ($intervenants as $id_enseignant) {
                if ($id_enseignant != $id_responsable && !in_array($id_enseignant, $co_responsables)) {
                    $query = $this->db->prepare('
                        INSERT INTO projet_responsable (id_projet, id_enseignant, role) 
                        VALUES (?, ?, "intervenant")
                    ');
                    $query->execute([$id_projet, $id_enseignant]);
                }
            }
            
            $this->db->commit();
            return $id_projet;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la création du projet: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getProjets() {
        $query = $this->db->prepare('
            SELECT 
                p.*,
                u.nom as responsable_nom,
                u.prenom as responsable_prenom,
                GROUP_CONCAT(DISTINCT 
                    CASE pr.role 
                        WHEN "co-responsable" THEN CONCAT(u2.prenom, " ", u2.nom)
                    END
                ) as co_responsables,
                GROUP_CONCAT(DISTINCT 
                    CASE pr.role 
                        WHEN "intervenant" THEN CONCAT(u2.prenom, " ", u2.nom)
                    END
                ) as intervenants
            FROM projet p 
            JOIN utilisateurs u ON p.id_responsable = u.id
            LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
            LEFT JOIN utilisateurs u2 ON pr.id_enseignant = u2.id
            GROUP BY p.id_projet
            ORDER BY p.date_creation DESC
        ');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProjetByGroupeId($id_groupe) {
        try {
            $query = $this->db->prepare('
                SELECT p.*
                FROM projet p
                JOIN groupe_projet gp ON p.id_projet = gp.id_projet
                WHERE gp.id_groupe = ?
            ');
            $query->execute([$id_groupe]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du projet par groupe: " . $e->getMessage());
            return null;
        }
    }
    
    public function getProjetById($id) {
        $query = $this->db->prepare('
            SELECT p.*, 
                   u.nom as responsable_nom,
                   u.prenom as responsable_prenom,
                   GROUP_CONCAT(DISTINCT 
                       CASE pr.role 
                           WHEN "co-responsable" THEN pr.id_enseignant 
                       END
                   ) as co_responsables,
                   GROUP_CONCAT(DISTINCT 
                       CASE pr.role 
                           WHEN "intervenant" THEN pr.id_enseignant 
                       END
                   ) as intervenants
            FROM projet p
            JOIN utilisateurs u ON p.id_responsable = u.id
            LEFT JOIN projet_responsable pr ON p.id_projet = pr.id_projet
            WHERE p.id_projet = ?
            GROUP BY p.id_projet
        ');
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function modifierProjet($id, $titre, $description, $annee, $semestre, $travail_groupe, $groupe_modifiable) {
        $query = $this->db->prepare('
            UPDATE projet 
            SET titre = ?,
                description = ?,
                annee = ?,
                semestre = ?,
                travail_groupe = ?,
                groupe_modifiable = ?
            WHERE id_projet = ?
        ');
        return $query->execute([
            $titre,
            $description,
            $annee,
            $semestre,
            $travail_groupe ? 1 : 0,
            $groupe_modifiable ? 1 : 0,
            $id
        ]);
    }
    
    public function supprimerProjet($id) {
        try {
            $this->db->beginTransaction();
            
            $query = $this->db->prepare('DELETE FROM evaluation WHERE id_projet = ?');
            $query->execute([$id]);
            
            $query = $this->db->prepare('DELETE FROM rendu WHERE id_projet = ?');
            $query->execute([$id]);
            
            $query = $this->db->prepare('DELETE FROM soutenance WHERE id_projet = ?');
            $query->execute([$id]);
            
            $query = $this->db->prepare('
                DELETE ge FROM groupe_etudiant ge
                JOIN groupe_projet gp ON ge.id_groupe = gp.id_groupe
                WHERE gp.id_projet = ?
            ');
            $query->execute([$id]);
            
            $query = $this->db->prepare('DELETE FROM groupe_projet WHERE id_projet = ?');
            $query->execute([$id]);
            
            $query = $this->db->prepare('DELETE FROM projet_responsable WHERE id_projet = ?');
            $query->execute([$id]);
            
            $query = $this->db->prepare('DELETE FROM projet WHERE id_projet = ?');
            $query->execute([$id]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur lors de la suppression du projet: " . $e->getMessage());
            return false;
        }
    }
    
    public function getEnseignantsDisponibles() {
        $query = $this->db->prepare('
            SELECT id, nom, prenom 
            FROM utilisateurs 
            WHERE role = "enseignant" 
            ORDER BY nom, prenom
        ');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function ajouterCollaborateur($id_projet, $id_enseignant, $role) {
        $query = $this->db->prepare('
            INSERT INTO projet_responsable (id_projet, id_enseignant, role) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE role = ?
        ');
        return $query->execute([$id_projet, $id_enseignant, $role, $role]);
    }
    
    public function supprimerCollaborateur($id_projet, $id_enseignant) {
        $query = $this->db->prepare('
            DELETE FROM projet_responsable 
            WHERE id_projet = ? AND id_enseignant = ?
        ');
        return $query->execute([$id_projet, $id_enseignant]);
    }

    public function creerGroupe($id_projet, $titre) {
        $query = $this->db->prepare('
            INSERT INTO groupe_projet (id_projet, titre) 
            VALUES (?, ?)
        ');
        $query->execute([$id_projet, $titre]);
        return $this->db->lastInsertId();
    }
    
    public function modifierGroupe($id_groupe, $titre) {
        $query = $this->db->prepare('
            UPDATE groupe_projet 
            SET titre = ?
            WHERE id_groupe = ?
        ');
        return $query->execute([$titre, $id_groupe]);
    }
    public function ajouterEtudiantGroupe($id_groupe, $id_etudiant) {
        $query = $this->db->prepare('
            INSERT INTO groupe_etudiant (id_groupe, id_etudiant) 
            VALUES (?, ?)
        ');
        return $query->execute([$id_groupe, $id_etudiant]);
    }
    public function retirerEtudiantGroupe($id_groupe, $id_etudiant) {
        $query = $this->db->prepare('
            DELETE FROM groupe_etudiant 
            WHERE id_groupe = ? AND id_etudiant = ?
        ');
        return $query->execute([$id_groupe, $id_etudiant]);
    }
    public function getGroupesProjet($id_projet) {
        $query = $this->db->prepare('
            SELECT gp.*, 
                   COUNT(ge.id_etudiant) as nb_etudiants,
                   GROUP_CONCAT(CONCAT(u.prenom, " ", u.nom)) as membres
            FROM groupe_projet gp
            LEFT JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
            LEFT JOIN utilisateurs u ON ge.id_etudiant = u.id
            WHERE gp.id_projet = ?
            GROUP BY gp.id_groupe
        ');
        $query->execute([$id_projet]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
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

    public function deleguerEvaluation($id_evaluation, $id_enseignant) {
        $query = $this->db->prepare('
            UPDATE evaluation 
            SET id_enseignant_delegue = ?
            WHERE id_evaluation = ?
        ');
        return $query->execute([$id_enseignant, $id_evaluation]);
    }

    public function ajouterChamp($id_projet, $nom, $description, $type, $modifiable_etudiant, $valeur = null) {
        $query = $this->db->prepare('
            INSERT INTO projet_champ (id_projet, nom, description, type, modifiable_etudiant, valeur)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        return $query->execute([
            $id_projet,
            $nom,
            $description,
            $type,
            $modifiable_etudiant ? 1 : 0,
            $valeur
        ]);
    }

    public function modifierChamp($id_champ, $nom, $description, $type, $modifiable_etudiant, $valeur = null) {
        $query = $this->db->prepare('
            UPDATE projet_champ 
            SET nom = ?,
                description = ?,
                type = ?,
                modifiable_etudiant = ?,
                valeur = ?
            WHERE id_champ = ?
        ');
        return $query->execute([
            $nom,
            $description,
            $type,
            $modifiable_etudiant ? 1 : 0,
            $valeur,
            $id_champ
        ]);
    }

    public function getChamps($id_projet) {
        $query = $this->db->prepare('
            SELECT * FROM projet_champ 
            WHERE id_projet = ?
            ORDER BY nom
        ');
        $query->execute([$id_projet]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateChampValue($id_champ, $valeur) {
        $query = $this->db->prepare('
            UPDATE projet_champ 
            SET valeur = ?
            WHERE id_champ = ?
        ');
        return $query->execute([$valeur, $id_champ]);
    }
}