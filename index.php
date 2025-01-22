<?php
// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/config.php';
require_once 'core/Router.php';

// Initialisation du routeur
$router = new Router();
$router->route();