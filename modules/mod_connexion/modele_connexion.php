<?php
require_once ROOT_PATH . '/core/Model.php';

class Modele_connexion extends Model {
    public function verifierUtilisateur($login, $password) {
        try {
            $query = $this->db->prepare('SELECT * FROM utilisateurs WHERE login = ?');
            $query->execute([$login]);
            $user = $query->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Ne pas renvoyer le mot de passe dans la session
                unset($user['password']);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    public function creerUtilisateur($login, $password, $role, $nom, $prenom, $email) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $query = $this->db->prepare('
                INSERT INTO utilisateurs (login, password, role, nom, prenom, email) 
                VALUES (?, ?, ?, ?, ?, ?)
            ');
            return $query->execute([$login, $password_hash, $role, $nom, $prenom, $email]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur: " . $e->getMessage());
            return false;
        }
    }
    
    public function loginExiste($login) {
        $query = $this->db->prepare('SELECT COUNT(*) as count FROM utilisateurs WHERE login = ?');
        $query->execute([$login]);
        return $query->fetch()['count'] > 0;
    }
    
    public function emailExiste($email) {
        $query = $this->db->prepare('SELECT COUNT(*) as count FROM utilisateurs WHERE email = ?');
        $query->execute([$email]);
        return $query->fetch()['count'] > 0;
    }
}