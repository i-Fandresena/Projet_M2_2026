<?php
/**
 * AuthController.php — Gestion de l'authentification
 * Supervision Bancaire — Architecture MVC
 */

require_once MODELS_PATH . '/UserModel.php';

class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ----------------------------------------------------------------
    // Affichage du formulaire de connexion
    // ----------------------------------------------------------------
    public function showLogin(): void
    {
        // Si déjà connecté, rediriger selon le rôle
        if (!empty($_SESSION['user_id'])) {
            $this->redirectByRole();
        }

        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);

        require VIEWS_PATH . '/auth/login.php';
    }

    // ----------------------------------------------------------------
    // Traitement du formulaire de connexion
    // ----------------------------------------------------------------
    public function handleLogin(): void
    {
        // Vérification CSRF
        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $_SESSION['login_error'] = 'Token de sécurité invalide. Veuillez réessayer.';
            redirect('?action=login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validation basique
        if ($username === '' || $password === '') {
            $_SESSION['login_error'] = 'Veuillez renseigner tous les champs.';
            redirect('?action=login');
        }

        $user = $this->userModel->authenticate($username, $password);

        if (!$user) {
            $_SESSION['login_error'] = 'Identifiant ou mot de passe incorrect.';
            redirect('?action=login');
        }

        // Régénérer l'ID de session pour éviter la fixation
        session_regenerate_id(true);

        // Stocker les informations de session
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role']     = $user['role'];

        // Transmettre le nom d'utilisateur au moteur MySQL (pour les triggers)
        Database::setAppUser($user['username']);

        $this->redirectByRole();
    }

    // ----------------------------------------------------------------
    // Déconnexion
    // ----------------------------------------------------------------
    public function logout(): void
    {
        // Vider et détruire la session
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        redirect('?action=login');
    }

    // ----------------------------------------------------------------
    // Redirection selon le rôle
    // ----------------------------------------------------------------
    private function redirectByRole(): void
    {
        if (($_SESSION['user_role'] ?? '') === 'admin') {
            redirect('?action=audit');
        } else {
            redirect('?action=versements');
        }
    }
}
