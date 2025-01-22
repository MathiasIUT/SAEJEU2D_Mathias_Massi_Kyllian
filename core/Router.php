<?php
class Router {
    private $defaultModule = 'connexion';
    private $defaultAction = 'index';

    public function route() {
        try {
            // Récupération du module et de l'action
            $module = isset($_GET['module']) ? filter_var($_GET['module'], FILTER_SANITIZE_STRING) : $this->defaultModule;
            $action = isset($_GET['action']) ? filter_var($_GET['action'], FILTER_SANITIZE_STRING) : $this->defaultAction;
            
            // Vérification de l'authentification
            if (!isset($_SESSION['user'])) {
                // Si l'utilisateur n'est pas connecté et n'essaie pas d'accéder au module connexion
                if ($module !== 'connexion') {
                    $_SESSION['flash'] = [
                        'type' => 'warning',
                        'message' => 'Veuillez vous connecter pour accéder à cette page.'
                    ];
                    header('Location: index.php?module=connexion');
                    exit;
                }
            } else {
                // Si l'utilisateur est connecté et essaie d'accéder à la connexion
                if ($module === 'connexion' && $action === 'index') {
                    header('Location: index.php?module=dashboard');
                    exit;
                }
                
                // Vérification des droits d'accès selon le rôle
                if (!$this->checkAccess($module, $action)) {
                    throw new Exception('Accès non autorisé');
                }
            }
            
            // Construction des chemins
            $moduleDir = ROOT_PATH . '/modules/mod_' . $module;
            $moduleFile = $moduleDir . '/module_' . $module . '.php';
            $controleurFile = $moduleDir . '/controleur_' . $module . '.php';
            $modeleFile = $moduleDir . '/modele_' . $module . '.php';
            $vueFile = $moduleDir . '/vue_' . $module . '.php';
            
            // Vérification de l'existence du module et des fichiers nécessaires
            if (!is_dir($moduleDir)) {
                throw new Exception('Module non trouvé: ' . $module);
            }
            
            if (!file_exists($moduleFile)) {
                throw new Exception('Fichier module non trouvé: ' . $moduleFile);
            }
            
            if (!file_exists($controleurFile)) {
                throw new Exception('Fichier contrôleur non trouvé: ' . $controleurFile);
            }
            
            if (!file_exists($modeleFile)) {
                throw new Exception('Fichier modèle non trouvé: ' . $modeleFile);
            }
            
            if (!file_exists($vueFile)) {
                throw new Exception('Fichier vue non trouvé: ' . $vueFile);
            }
            
            // Chargement des fichiers nécessaires
            require_once $moduleFile;
            require_once $controleurFile;
            require_once $modeleFile;
            require_once $vueFile;
            
            // Construction du nom de la classe
            $moduleClass = 'Module_' . $module;
            
            // Vérification de l'existence de la classe
            if (!class_exists($moduleClass)) {
                throw new Exception('Classe du module non trouvée: ' . $moduleClass);
            }
            
            // Instanciation et exécution
            $moduleInstance = new $moduleClass();
            if (!method_exists($moduleInstance, 'execute')) {
                throw new Exception('Méthode execute non trouvée dans le module: ' . $module);
            }
            
            $moduleInstance->execute($action);
            
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function checkAccess($module, $action) {
        // Permettre l'accès au module de connexion sans authentification
        if ($module === 'connexion') {
            return true;
        }

        if (!isset($_SESSION['user'])) {
            return false;
        }

        $role = $_SESSION['user']['role'];
        
        // Définition des permissions
        $permissions = [
            'admin' => ['*'],
            'enseignant' => [
                'dashboard', 
                'projet', 
                'rendu', 
                'messagerie', 
                'soutenance', 
                'ressource',
                'evaluation'
            ],
            'etudiant' => [
                'dashboard', 
                'rendu', 
                'messagerie', 
                'soutenance', 
                'ressource'
            ]
        ];

        // Vérification des permissions
        if (isset($permissions[$role])) {
            return in_array('*', $permissions[$role]) || in_array($module, $permissions[$role]);
        }

        return false;
    }

    private function handleError($e) {
        // Log de l'erreur
        error_log($e->getMessage());
        
        // Affichage de l'erreur appropriée
        if ($e->getMessage() === 'Accès non autorisé') {
            header('HTTP/1.1 403 Forbidden');
            include(ROOT_PATH . '/views/403.php');
        } else {
            if (defined('DEBUG') && DEBUG === true) {
                // En mode debug, afficher les détails de l'erreur
                echo '<div class="container mt-4"><div class="alert alert-danger">';
                echo '<h4>Erreur:</h4>';
                echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
                echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
                echo '</div></div>';
            } else {
                // En production, afficher la page 404
                header('HTTP/1.1 404 Not Found');
                include(ROOT_PATH . '/views/404.php');
            }
        }
    }
}