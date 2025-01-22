<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_messagerie extends Model {
    public function getMessages($id_utilisateur) {
        $query = $this->db->prepare('
            SELECT m.*, 
                   u1.login as expediteur_login,
                   u2.login as destinataire_login
            FROM message m
            JOIN utilisateurs u1 ON m.id_expediteur = u1.id
            JOIN utilisateurs u2 ON m.id_destinataire = u2.id
            WHERE m.id_expediteur = ? OR m.id_destinataire = ?
            ORDER BY m.date_envoi DESC
        ');
        $query->execute([$id_utilisateur, $id_utilisateur]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function envoyerMessage($id_expediteur, $id_destinataire, $sujet, $contenu) {
        $query = $this->db->prepare('
            INSERT INTO message (id_expediteur, id_destinataire, sujet, contenu, date_envoi)
            VALUES (?, ?, ?, ?, NOW())
        ');
        return $query->execute([$id_expediteur, $id_destinataire, $sujet, $contenu]);
    }
    
    public function getMessageById($id_message) {
        $query = $this->db->prepare('
            SELECT m.*, 
                   u1.login as expediteur_login,
                   u2.login as destinataire_login
            FROM message m
            JOIN utilisateurs u1 ON m.id_expediteur = u1.id
            JOIN utilisateurs u2 ON m.id_destinataire = u2.id
            WHERE m.id_message = ?
        ');
        $query->execute([$id_message]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function marquerCommeLu($id_message) {
        $query = $this->db->prepare('UPDATE message SET lu = true WHERE id_message = ?');
        return $query->execute([$id_message]);
    }
    
    public function supprimerMessage($id_message, $id_utilisateur) {
        $query = $this->db->prepare('
            DELETE FROM message 
            WHERE id_message = ? 
            AND (id_expediteur = ? OR id_destinataire = ?)
        ');
        return $query->execute([$id_message, $id_utilisateur, $id_utilisateur]);
    }
    
    public function getUtilisateursDisponibles() {
        $query = $this->db->prepare('
            SELECT id, login, nom, prenom, role
            FROM utilisateurs 
            WHERE id != ?
            ORDER BY role, nom, prenom
        ');
        $query->execute([$_SESSION['user']['id']]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNbMessagesNonLus($id_utilisateur) {
        $query = $this->db->prepare('
            SELECT COUNT(*) as nb 
            FROM message 
            WHERE id_destinataire = ? AND lu = false
        ');
        $query->execute([$id_utilisateur]);
        return $query->fetch(PDO::FETCH_ASSOC)['nb'];
    }
}