<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — <?= e(APP_NAME) ?></title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>
        :root {
            --color-secondary : #0F172A;
            --color-accent    : #2563EB;
            --color-border    : #E2E8F0;
            --color-text      : #1E293B;
            --color-muted     : #64748B;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F1F5F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 1rem;
        }

        .login-card {
            background: #FFFFFF;
            border: 1px solid var(--color-border);
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .login-header {
            background: var(--color-secondary);
            padding: 2rem 2rem 1.5rem;
            text-align: center;
        }

        .login-logo {
            width: 48px;
            height: 48px;
            background: var(--color-accent);
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            color: #FFFFFF;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.15rem;
        }

        .login-header p {
            color: #94A3B8;
            font-size: 0.75rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .login-body {
            padding: 1.75rem 2rem;
        }

        .form-label {
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--color-text);
            margin-bottom: 0.3rem;
        }

        .form-control {
            font-size: 0.8rem;
            border-color: var(--color-border);
            border-radius: 6px;
            padding: 0.5rem 0.875rem;
        }

        .form-control:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }

        .btn-login {
            background: var(--color-accent);
            border: none;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.65rem;
            border-radius: 6px;
            width: 100%;
            transition: background 0.15s;
        }

        .btn-login:hover {
            background: #1d4ed8;
        }

        .input-group-text {
            background: #F8FAFC;
            border-color: var(--color-border);
            color: var(--color-muted);
            font-size: 0.8rem;
        }

        .alert {
            font-size: 0.8rem;
            border-radius: 6px;
        }

        .login-footer-text {
            text-align: center;
            color: var(--color-muted);
            font-size: 0.72rem;
            margin-top: 1.25rem;
        }

        .demo-credentials {
            background: #F8FAFC;
            border: 1px solid var(--color-border);
            border-radius: 6px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
        }

        .demo-credentials p {
            font-size: 0.72rem;
            color: var(--color-muted);
            margin: 0 0 0.25rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .demo-credentials code {
            font-size: 0.75rem;
            color: var(--color-accent);
            background: none;
            padding: 0;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- En-tête carte -->
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">
                <i class="bi bi-bank2 text-white" style="font-size:1.4rem;"></i>
            </div>
            <h1><?= e(APP_NAME) ?></h1>
            <p>Portail de supervision — Connexion</p>
        </div>

        <div class="login-body">

            <!-- Message d'erreur -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                    <span><?= e($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Identifiants de démo -->
            <div class="demo-credentials">
                <p>Comptes de demonstration</p>
                <div class="d-flex flex-column gap-1">
                    <span><code>admin</code> / <code>Admin123!</code> &mdash; role Admin</span>
                    <span><code>user1</code> / <code>User123!</code> &mdash; role User</span>
                </div>
            </div>

            <!-- Formulaire de connexion -->
            <form method="post" action="<?= BASE_URL ?>?action=login" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

                <!-- Nom d'utilisateur -->
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text"
                               id="username"
                               name="username"
                               class="form-control"
                               placeholder="Entrez votre identifiant"
                               value="<?= e($_POST['username'] ?? '') ?>"
                               autocomplete="username"
                               required>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="mb-4">
                    <label for="password" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="Entrez votre mot de passe"
                               autocomplete="current-password"
                               required>
                    </div>
                </div>

                <!-- Bouton de connexion -->
                <button type="submit" class="btn-login">
                    <i class="bi bi-arrow-right-circle me-1"></i>
                    Se connecter
                </button>
            </form>

        </div>
    </div>

    <div class="login-footer-text">
        <?= e(APP_NAME) ?> &mdash; v<?= e(APP_VERSION) ?> &mdash; <?= date('Y') ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmHNjnGGm8dvyAAd5uGPEDZe3YE7K"
        crossorigin="anonymous"></script>
</body>
</html>
