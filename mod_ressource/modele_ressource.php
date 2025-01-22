<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_ressource extends Model {
    public function getRessources($id_projet = null) {
        try {
            $sql = '
                SELECT r.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       p.titre as projet_titre
                FROM ressource r
                LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id
                LEFT JOIN projet p ON r.id_projet = p.id_projet
            ';
            
            if ($id_projet) {
                $sql .= ' WHERE r.id_projet = ?';
                $query = $this->db->prepare($sql . ' ORDER BY r.date_creation DESC');
                $query->execute([$id_projet]);
            } else {
                $query = $this->db->prepare($sql . ' ORDER BY r.date_creation DESC');
                $query->execute();
            }
            
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des ressources: " . $e->getMessage());
            return [];
        }
    }
    
    public function ajouterRessource($titre, $description, $type, $lien = null, $fichier = null, $id_projet = null) {
        try {
            // Log des données reçues pour le débogage
            error_log("Tentative d'ajout de ressource avec les données suivantes:");
            error_log("Titre: " . $titre);
            error_log("Type: " . $type);
            error_log("ID Utilisateur: " . ($_SESSION['user']['id'] ?? 'non défini'));
            
            // Vérification de la connexion à la base de données
            if (!$this->db) {
                error_log("Erreur: La connexion à la base de données n'est pas établie");
                return false;
            }

            $query = $this->db->prepare('
                INSERT INTO ressource (
                    titre, 
                    description, 
                    type, 
                    lien, 
                    fichier, 
                    id_utilisateur, 
                    id_projet,
                    date_creation
                ) 
                VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ');

            $params = [
                $titre,
                $description,
                $type,
                $lien,
                $fichier,
                $_SESSION['user']['id'],
                $id_projet
            ];

            // Log de la requête SQL pour le débogage
            error_log("Requête SQL préparée avec les paramètres: " . print_r($params, true));

            $result = $query->execute($params);
            
            if (!$result) {
                error_log("Erreur lors de l'exécution de la requête: " . print_r($query->errorInfo(), true));
                return false;
            }

            return true;
        } catch (PDOException $e) {
            error_log("Exception PDO lors de l'ajout de la ressource: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            return false;
        } catch (Exception $e) {
            error_log("Exception générale lors de l'ajout de la ressource: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            return false;
        }
    }

    public function getRessourceById($id_ressource) {
        try {
            $query = $this->db->prepare('
                SELECT r.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom,
                       p.titre as projet_titre
                FROM ressource r
                LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id
                LEFT JOIN projet p ON r.id_projet = p.id_projet
                WHERE r.id_ressource = ?
            ');
            $query->execute([$id_ressource]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la ressource: " . $e->getMessage());
            return null;
        }
    }

    public function supprimerRessource($id_ressource) {
        try {
            // Récupérer d'abord les informations de la ressource pour le fichier
            $ressource = $this->getRessourceById($id_ressource);
            
            $query = $this->db->prepare('DELETE FROM ressource WHERE id_ressource = ?');
            $success = $query->execute([$id_ressource]);
            
            // Si la suppression a réussi et qu'il y avait un fichier, le supprimer
            if ($success && $ressource && $ressource['fichier']) {
                $chemin_fichier = ROOT_PATH . '/uploads/ressources/' . $ressource['fichier'];
                if (file_exists($chemin_fichier)) {
                    unlink($chemin_fichier);
                }
            }
            
            return $success;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la ressource: " . $e->getMessage());
            return false;
        }
    }

    public function modifierRessource($id_ressource, $titre, $description, $type, $lien = null, $fichier = null) {
        try {
            $params = [
                'titre' => $titre,
                'description' => $description,
                'type' => $type,
                'lien' => $lien,
                'id_ressource' => $id_ressource
            ];

            $sql = '
                UPDATE ressource 
                SET titre = :titre,
                    description = :description,
                    type = :type,
                    lien = :lien
            ';

            if ($fichier !== null) {
                $sql .= ', fichier = :fichier';
                $params['fichier'] = $fichier;
            }

            $sql .= ' WHERE id_ressource = :id_ressource';
            
            $query = $this->db->prepare($sql);
            return $query->execute($params);
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification de la ressource: " . $e->getMessage());
            return false;
        }
    }

    public function getProjetsEnseignant($id_enseignant) {
        try {
            $query = $this->db->prepare('
                SELECT DISTINCT p.*
                FROM projet p
                JOIN projet_responsable pr ON p.id_projet = pr.id_projet
                WHERE pr.id_enseignant = ?
                ORDER BY p.date_creation DESC
            ');
            $query->execute([$id_enseignant]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des projets: " . $e->getMessage());
            return [];
        }
    }

    public function getRessourcesProjet($id_projet) {
        try {
            $query = $this->db->prepare('
                SELECT r.*, 
                       u.nom as auteur_nom, 
                       u.prenom as auteur_prenom
                FROM ressource r
                LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id
                WHERE r.id_projet = ?
                ORDER BY r.date_creation DESC
            ');
            $query->execute([$id_projet]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des ressources du projet: " . $e->getMessage());
            return [];
        }
    }
}