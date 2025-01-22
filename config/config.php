<?php
// Mode debug (à mettre à false en production)
define('DEBUG', true);

// Configuration des chemins
define('ROOT_PATH', dirname(__DIR__));
define('MODULE_PATH', ROOT_PATH . '/modules');

// Configuration de la base de données
$db_config = require_once ROOT_PATH . '/config/database.php';

// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    // Configuration des sessions
    session_name('GSAE_SESSION');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => !DEBUG,
        'httponly' => true
    ]);
    session_start();
}

// Fonction d'autoload des classes
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/core/',
        ROOT_PATH . '/modules/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});