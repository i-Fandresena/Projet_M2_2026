<?php
/**
 * config.php — Configuration globale de l'application
 * Supervision Bancaire — Architecture MVC
 */

// ------------------------------------------------------------
// Environnement
// ------------------------------------------------------------
define('APP_ENV',  'development');   // 'development' | 'production'
define('APP_NAME', 'Supervision Bancaire');
define('APP_VERSION', '1.0.0');

// ------------------------------------------------------------
// Chemins absolus
// ------------------------------------------------------------
define('ROOT_PATH',       dirname(__DIR__));
define('CONFIG_PATH',     ROOT_PATH . '/config');
define('MODELS_PATH',     ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('VIEWS_PATH',      ROOT_PATH . '/views');

// ------------------------------------------------------------
// URL de base (adapter selon votre configuration XAMPP)
// ------------------------------------------------------------
define('BASE_URL', 'http://localhost/BDA/public/');

// ------------------------------------------------------------
// Session
// ------------------------------------------------------------
define('SESSION_LIFETIME', 3600);   // 1 heure en secondes
define('SESSION_NAME',     'SUPERVISION_SESS');

// ------------------------------------------------------------
// Pagination
// ------------------------------------------------------------
define('ITEMS_PER_PAGE', 10);

// ------------------------------------------------------------
// Gestion des erreurs selon l'environnement
// ------------------------------------------------------------
if (APP_ENV === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ------------------------------------------------------------
// Démarrage et sécurisation de la session
// ------------------------------------------------------------
function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => '/',
            'secure'   => false,   // Passer à true en HTTPS
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }
}

// ------------------------------------------------------------
// Protection CSRF
// ------------------------------------------------------------
function generateCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool
{
    return isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

// ------------------------------------------------------------
// Redirection propre
// ------------------------------------------------------------
function redirect(string $path): never
{
    header('Location: ' . BASE_URL . ltrim($path, '/'));
    exit;
}

// ------------------------------------------------------------
// Vérification de rôle
// ------------------------------------------------------------
function requireAuth(): void
{
    if (empty($_SESSION['user_id'])) {
        redirect('?action=login');
    }
}

function requireRole(string $role): void
{
    requireAuth();
    if (($_SESSION['user_role'] ?? '') !== $role) {
        redirect('?action=unauthorized');
    }
}

function isAdmin(): bool
{
    return ($_SESSION['user_role'] ?? '') === 'admin';
}

function isUser(): bool
{
    return ($_SESSION['user_role'] ?? '') === 'user';
}

// ------------------------------------------------------------
// Échappement HTML
// ------------------------------------------------------------
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}
