<?php
abstract class Model {
    protected $db;
    
    public function __construct() {
        try {
            $this->db = Connexion::getConnexion();
        } catch (PDOException $e) {
            // Log l'erreur
            error_log('Erreur de connexion à la base de données : ' . $e->getMessage());
            
            // En développement, propager l'erreur
            if (defined('DEBUG') && DEBUG === true) {
                throw $e;
            }
            
            // En production, message générique
            die('Une erreur est survenue. Veuillez réessayer plus tard.');
        }
    }

    protected function sanitize($value) {
        if (is_array($value)) {
            return array_map([$this, 'sanitize'], $value);
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    protected function logError($message) {
        error_log(date('Y-m-d H:i:s') . ' - ' . $message);
    }
}