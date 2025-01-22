<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_utilisateur extends Model {
    public function creerUtilisateur($login, $password, $role) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->db->prepare('INSERT INTO utilisateurs (login, password, role) VALUES (?, ?, ?)');
        return $query->execute([$login, $password_hash, $role]);
    }
    
    public function getUtilisateurs() {
        $query = $this->db->prepare('SELECT id, login, role FROM utilisateurs');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUtilisateurById($id) {
        $query = $this->db->prepare('SELECT id, login, role FROM utilisateurs WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    public function modifierUtilisateur($id, $login, $password, $role) {
        if ($password) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = $this->db->prepare('UPDATE utilisateurs SET login = ?, password = ?, role = ? WHERE id = ?');
            return $query->execute([$login, $password_hash, $role, $id]);
        } else {
            $query = $this->db->prepare('UPDATE utilisateurs SET login = ?, role = ? WHERE id = ?');
            return $query->execute([$login, $role, $id]);
        }
    }
    
    public function supprimerUtilisateur($id) {
        $query = $this->db->prepare('DELETE FROM utilisateurs WHERE id = ?');
        return $query->execute([$id]);
    }
}