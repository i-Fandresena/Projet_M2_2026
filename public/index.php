<?php
/**
 * public/index.php — Point d'entrée unique (Front Controller)
 * Supervision Bancaire — Architecture MVC
 *
 * Toutes les requêtes HTTP sont routées par ce fichier.
 * .htaccess redirige le trafic vers ce fichier.
 */

// ----------------------------------------------------------------
// 1. Chargement de la configuration globale
// ----------------------------------------------------------------
require_once dirname(__DIR__) . '/config/config.php';
require_once CONFIG_PATH      . '/database.php';

// ----------------------------------------------------------------
// 2. Démarrage de la session sécurisée
// ----------------------------------------------------------------
startSecureSession();

// ----------------------------------------------------------------
// 3. Transmission de @app_user à MySQL si session active
//    (Permet aux triggers de journaliser le bon utilisateur)
// ----------------------------------------------------------------
if (!empty($_SESSION['user_username'])) {
    try {
        Database::setAppUser($_SESSION['user_username']);
    } catch (RuntimeException) {
        // La connexion DB peut échouer ici si l'utilisateur n'est pas encore sur une page DB
        // On ignore silencieusement — sera re-tentée à la première requête DB
    }
}

// ----------------------------------------------------------------
// 4. Routeur simple basé sur le paramètre GET 'action'
// ----------------------------------------------------------------
$action = $_GET['action'] ?? 'login';

// Validation de l'action (whitelist)
$allowedGetActions = [
    'login', 'logout',
    'versements',
    'audit',
    'unauthorized',
];

$allowedPostActions = [
    'login',
    'logout',
    'versement_store',
    'versement_update',
    'versement_delete',
];

// Vérification de la méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

try {
    // --------------------------------------------------------
    // Routes GET
    // --------------------------------------------------------
    if ($method === 'GET') {

        switch ($action) {

            case 'login':
                require_once CONTROLLERS_PATH . '/AuthController.php';
                $controller = new AuthController();
                $controller->showLogin();
                break;

            case 'versements':
                require_once CONTROLLERS_PATH . '/VersementController.php';
                $controller = new VersementController();
                $controller->index();
                break;

            case 'audit':
                require_once CONTROLLERS_PATH . '/AuditController.php';
                $controller = new AuditController();
                $controller->index();
                break;

            case 'unauthorized':
                http_response_code(403);
                renderErrorPage(403, 'Acces refuse', 'Vous n\'avez pas les droits necessaires pour acceder a cette page.');
                break;

            default:
                http_response_code(404);
                renderErrorPage(404, 'Page introuvable', 'La page demandee n\'existe pas.');
                break;
        }

    // --------------------------------------------------------
    // Routes POST
    // --------------------------------------------------------
    } elseif ($method === 'POST') {

        switch ($action) {

            case 'login':
                require_once CONTROLLERS_PATH . '/AuthController.php';
                $controller = new AuthController();
                $controller->handleLogin();
                break;

            case 'logout':
                require_once CONTROLLERS_PATH . '/AuthController.php';
                $controller = new AuthController();
                $controller->logout();
                break;

            case 'versement_store':
                require_once CONTROLLERS_PATH . '/VersementController.php';
                $controller = new VersementController();
                $controller->store();
                break;

            case 'versement_update':
                require_once CONTROLLERS_PATH . '/VersementController.php';
                $controller = new VersementController();
                $controller->update();
                break;

            case 'versement_delete':
                require_once CONTROLLERS_PATH . '/VersementController.php';
                $controller = new VersementController();
                $controller->destroy();
                break;

            default:
                http_response_code(405);
                renderErrorPage(405, 'Methode non autorisee', 'Cette action n\'accepte pas la methode POST.');
                break;
        }

    } else {
        // Méthodes HTTP non gérées
        http_response_code(405);
        renderErrorPage(405, 'Methode non autorisee', 'Seules les methodes GET et POST sont prises en charge.');
    }

} catch (RuntimeException $e) {
    // Erreurs métier (connexion DB, etc.)
    http_response_code(500);
    renderErrorPage(500, 'Erreur serveur', $e->getMessage());
}


// ----------------------------------------------------------------
// Fonction utilitaire : page d'erreur inline
// ----------------------------------------------------------------
function renderErrorPage(int $code, string $title, string $message): void
{
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= e($code . ' — ' . $title) ?></title>
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; background: #F1F5F9; }
            .error-box { max-width: 480px; margin: 5rem auto; text-align: center; }
            .error-code { font-size: 4rem; font-weight: 700; color: #1E293B; line-height: 1; }
            .error-title { font-size: 1.1rem; font-weight: 600; color: #1E293B; margin: 0.5rem 0; }
            .error-msg { font-size: 0.85rem; color: #64748B; }
        </style>
    </head>
    <body>
        <div class="error-box">
            <div class="error-code"><?= e($code) ?></div>
            <div class="error-title"><?= e($title) ?></div>
            <p class="error-msg"><?= e($message) ?></p>
            <a href="<?= BASE_URL ?>?action=login" class="btn btn-sm mt-3"
               style="background:#2563EB;color:#fff;font-size:0.8rem;">
                Retour a l'accueil
            </a>
        </div>
    </body>
    </html>
    <?php
}
