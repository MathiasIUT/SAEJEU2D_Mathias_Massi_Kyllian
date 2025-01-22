<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_ressource extends Model {
    public function getRessources() {
        // Récupérer toutes les ressources avec les informations de l'auteur
        $sql = "SELECT r.*, u.nom as auteur_nom, u.prenom as auteur_prenom 
               FROM ressource r
               LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id
               ORDER BY r.date_creation DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function ajouterRessource($titre, $description, $type, $lien, $fichier, $promotion) {
        $sql = "INSERT INTO ressource (titre, description, type, lien, fichier, promotion, id_utilisateur, date_creation) 
                VALUES (:titre, :description, :type, :lien, :fichier, :promotion, :id_utilisateur, NOW())";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titre' => $titre,
            ':description' => $description,
            ':type' => $type,
            ':lien' => $lien,
            ':fichier' => $fichier,
            ':promotion' => $promotion,
            ':id_utilisateur' => $_SESSION['user']['id']
        ]);
    }

    public function modifierRessource($id, $titre, $description, $type, $lien, $promotion, $fichier = null) {
        $sql = "UPDATE ressource 
                SET titre = :titre, 
                    description = :description, 
                    type = :type, 
                    lien = :lien,
                    promotion = :promotion";
        
        $params = [
            ':titre' => $titre,
            ':description' => $description,
            ':type' => $type,
            ':lien' => $lien,
            ':promotion' => $promotion,
            ':id' => $id
        ];

        if ($fichier !== null) {
            $sql .= ", fichier = :fichier";
            $params[':fichier'] = $fichier;
        }

        $sql .= " WHERE id_ressource = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getRessourceById($id) {
        $sql = "SELECT r.*, u.nom as auteur_nom, u.prenom as auteur_prenom 
                FROM ressource r
                LEFT JOIN utilisateurs u ON r.id_utilisateur = u.id
                WHERE r.id_ressource = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function supprimerRessource($id) {
        $sql = "DELETE FROM ressource WHERE id_ressource = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}