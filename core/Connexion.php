<?php
class Connexion {
    private static $instance = null;
    private $connection = null;

    private function __construct() {
        try {
            $config = require ROOT_PATH . '/config/database.php';
            
            if (!is_array($config)) {
                throw new Exception('La configuration de la base de données est invalide');
            }
            
            if (!isset($config['host']) || !isset($config['dbname']) || !isset($config['charset']) ||
                !isset($config['user']) || !isset($config['pass']) || !isset($config['options'])) {
                throw new Exception('Configuration de la base de données incomplète');
            }
            
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );
            
            $this->connection = new PDO(
                $dsn,
                $config['user'],
                $config['pass'],
                $config['options']
            );
            
        } catch (PDOException $e) {
            $this->handleError($e);
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getConnexion() {
        $instance = self::getInstance();
        if ($instance->connection === null) {
            throw new Exception('La connexion à la base de données n\'a pas pu être établie');
        }
        return $instance->connection;
    }

    private function handleError($e) {
        // Log l'erreur
        error_log(sprintf(
            "Erreur de connexion MySQL [%s]: %s\nFichier: %s\nLigne: %d",
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));

        // En développement, afficher les détails
        if (defined('DEBUG') && DEBUG === true) {
            throw $e;
        }

        // En production, afficher un message générique
        die('Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.');
    }

    // Empêcher le clonage de l'instance
    private function __clone() {}

    // Empêcher la désérialisation de l'instance
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}