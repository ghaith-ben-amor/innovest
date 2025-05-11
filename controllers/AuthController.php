<?php
require_once 'models/User.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            $userModel = new User();
            $user = $userModel->getByUsername($username);
    
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['iduser'];
                $_SESSION['is_admin'] = ($user['type'] === 'admin');
                $_SESSION['user_type'] = $user['type'];
                $_SESSION['role'] = $user['type'];  // Added to fix admin dashboard access
                
                // Debug
                error_log("Login successful for user: " . $username);
                error_log("User ID: " . $user['iduser']);
                error_log("Admin status: " . ($_SESSION['is_admin'] ? 'true' : 'false'));
                error_log("User type: " . $user['type']);
                
                if ($_SESSION['is_admin']) {
                    header('Location: index.php?action=dashboard');
                } else {
                    header('Location: index.php?action=home');
                }
                exit;
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect";
                include 'views/auth/login.php';
            }
        } else {
            include 'views/auth/login.php';
        }
    }
    

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $type = $_POST['type'] ?? 'user';
            
            $userModel = new User();
            if ($userModel->create($username, $password, $type)) {
                // Récupérer l'utilisateur créé pour obtenir son ID
                $user = $userModel->getByUsername($username);
                if ($user) {
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $user['iduser'];
                    $_SESSION['is_admin'] = ($type === 'admin');
                    $_SESSION['user_type'] = $type;
                    $_SESSION['role'] = $type;
                    
                    error_log("Registration successful for user: " . $username);
                    error_log("User ID: " . $user['iduser']);
                    error_log("Admin status: " . ($_SESSION['is_admin'] ? 'true' : 'false'));
                    
                    // Rediriger vers le dashboard admin si l'utilisateur est un admin
                    if ($type === 'admin') {
                        header('Location: index.php?action=adminDashboard');
                    } else {
                        header('Location: index.php?action=home');
                    }
                    exit;
                }
            }
            $error = "Erreur lors de l'inscription.";
            include 'views/auth/register.php';
        } else {
            include 'views/auth/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
