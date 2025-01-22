<?php
// Configuration de la base de données
return [
    'host' => 'localhost',
    'dbname' => 'dutinfopw201613bis',
    'charset' => 'utf8',
    'user' => 'root', // Remplacez par votre nom d'utilisateur MySQL
    'pass' => '',     // Remplacez par votre mot de passe MySQL
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_EMULATE_PREPARES => false
    ]
];