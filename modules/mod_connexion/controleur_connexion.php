<?php
require_once 'modele_connexion.php';
require_once 'vue_connexion.php';

class Controleur_connexion {
    private $modele;
    private $vue;
    
    public function __construct() {
        $this->modele = new Modele_connexion();
        $this->vue = new Vue_connexion();
    }
    
    public function index() {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if (isset($_SESSION['user'])) {
            header('Location: index.php?module=dashboard');
            exit;
        }
        $this->afficherFormulaire();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($login) || empty($password)) {
                $this->vue->afficherFormulaire(['error' => 'Veuillez remplir tous les champs']);
                return;
            }
            
            if ($user = $this->modele->verifierUtilisateur($login, $password)) {
                $_SESSION['user'] = $user;
                $_SESSION['flash'] = [
                    'type' => 'success',
                    'message' => 'Connexion réussie'
                ];
                header('Location: index.php?module=dashboard');
                exit;
            } else {
                $this->vue->afficherFormulaire(['error' => 'Identifiants incorrects']);
            }
        } else {
            $this->vue->afficherFormulaire();
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'] ?? '';
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $role = $_POST['role'] ?? '';
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            
            // Validation
            $errors = [];
            
            if (empty($login)) $errors[] = "L'identifiant est requis";
            if (empty($password)) $errors[] = "Le mot de passe est requis";
            if ($password !== $password_confirm) $errors[] = "Les mots de passe ne correspondent pas";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
            if (!in_array($role, ['etudiant', 'enseignant'])) $errors[] = "Rôle invalide";
            
            if (empty($errors)) {
                if ($this->modele->loginExiste($login)) {
                    $errors[] = "Cet identifiant est déjà utilisé";
                }
                
                if ($this->modele->emailExiste($email)) {
                    $errors[] = "Cet email est déjà utilisé";
                }
            }
            
            if (empty($errors)) {
                if ($this->modele->creerUtilisateur($login, $password, $role, $nom, $prenom, $email)) {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => 'Inscription réussie. Vous pouvez maintenant vous connecter.'
                    ];
                    header('Location: index.php?module=connexion');
                    exit;
                } else {
                    $errors[] = "Erreur lors de l'inscription";
                }
            }
            
            if (!empty($errors)) {
                $this->vue->afficherRegister([
                    'error' => implode('<br>', $errors),
                    'old' => compact('login', 'role', 'nom', 'prenom', 'email')
                ]);
                return;
            }
        } else {
            $this->vue->afficherRegister();
        }
    }
    
    public function logout() {
        session_destroy();
        session_start();
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Vous avez été déconnecté avec succès'
        ];
        header('Location: index.php?module=connexion');
        exit;
    }
    
    public function afficherFormulaire($data = []) {
        $this->vue->afficherFormulaire($data);
    }
}