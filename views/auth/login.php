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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #030712;
            position: relative;
        }

        /* ── Fond animé ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
            background: radial-gradient(ellipse at 20% 50%, #0c1a4a 0%, #030712 60%),
                        radial-gradient(ellipse at 80% 20%, #0d2347 0%, transparent 50%);
        }

        /* Lignes de grille */
        .bg-scene::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(37,99,235,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37,99,235,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* Orbes lumineux */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: drift linear infinite;
        }
        .orb-1 { width: 600px; height: 600px; background: #1d4ed8; top: -200px; left: -100px; animation-duration: 20s; }
        .orb-2 { width: 400px; height: 400px; background: #4f46e5; bottom: -100px; right: 10%; animation-duration: 25s; animation-direction: reverse; }
        .orb-3 { width: 300px; height: 300px; background: #0891b2; top: 40%; right: -80px; animation-duration: 18s; animation-delay: -6s; }

        @keyframes drift {
            0%   { transform: translate(0, 0) rotate(0deg); }
            33%  { transform: translate(30px, -40px) rotate(120deg); }
            66%  { transform: translate(-20px, 20px) rotate(240deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        /* Particules flottantes */
        .particle {
            position: absolute;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: rgba(99,179,237,0.6);
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.6; }
            100% { transform: translateY(-10vh) scale(1.5); opacity: 0; }
        }

        /* ── Séparateur droit : panneau info ── */
        .login-scene {
            position: relative;
            z-index: 10;
            width: 100vw;
            height: 100vh;
            display: grid;
            grid-template-columns: 1fr 480px;
        }

        .scene-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem 5rem;
            animation: fadeSlideLeft 0.8s ease both;
        }
        @keyframes fadeSlideLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .scene-left .app-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(37,99,235,0.15);
            border: 1px solid rgba(37,99,235,0.3);
            color: #93c5fd;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0.35rem 0.9rem;
            border-radius: 100px;
            margin-bottom: 2rem;
            width: fit-content;
        }

        .scene-left h1 {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            color: #f8fafc;
            line-height: 1.15;
            margin-bottom: 1.25rem;
        }

        .scene-left h1 span {
            background: linear-gradient(135deg, #60a5fa, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .scene-left p {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 440px;
            margin-bottom: 3rem;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .feature-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        /* ── Panneau formulaire ── */
        .scene-right {
            background: rgba(15,23,42,0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-left: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.75rem;
            animation: fadeSlideRight 0.8s ease both;
            position: relative;
            overflow: hidden;
        }
        @keyframes fadeSlideRight {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ligne déco top */
        .scene-right::before {
            content: '';
            position: absolute;
            top: 0; left: 2.75rem; right: 2.75rem;
            height: 2px;
            background: linear-gradient(90deg, transparent, #3b82f6, #6366f1, transparent);
        }

        .form-logo {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #fff;
            margin-bottom: 1.75rem;
            box-shadow: 0 0 30px rgba(37,99,235,0.4);
        }

        .form-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 0.4rem;
        }

        .form-subtitle {
            color: #475569;
            font-size: 0.82rem;
            margin-bottom: 2rem;
        }

        /* Demo pill */
        .demo-pill {
            background: rgba(37,99,235,0.08);
            border: 1px solid rgba(37,99,235,0.2);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.75rem;
            display: flex;
            gap: 1rem;
        }

        .demo-pill-label {
            font-size: 0.62rem;
            color: #60a5fa;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.3rem;
        }

        .demo-pill code {
            font-size: 0.75rem;
            color: #94a3b8;
            background: none;
        }

        .demo-pill-sep {
            width: 1px;
            background: rgba(255,255,255,0.07);
        }

        /* Champs */
        .field-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .field-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-input {
            width: 100%;
            background: rgba(30,41,59,0.6);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            color: #f1f5f9;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            padding: 0.8rem 0.875rem 0.8rem 2.75rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field-input::placeholder { color: #334155; }

        .field-input:focus {
            border-color: rgba(59,130,246,0.6);
            background: rgba(30,41,59,0.9);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }

        .field-group:focus-within .field-icon { color: #60a5fa; }

        /* Bouton */
        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.15s;
            margin-top: 0.5rem;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px rgba(37,99,235,0.45);
        }

        .btn-submit:hover::after { opacity: 1; }

        .btn-submit:active { transform: translateY(0); }

        /* Alerte */
        .login-alert {
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 0.8rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
            color: #1e293b;
            font-size: 0.7rem;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .login-scene { grid-template-columns: 1fr; }
            .scene-left { display: none; }
            .scene-right {
                border-left: none;
                border-top: 1px solid rgba(255,255,255,0.06);
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<!-- Fond animé -->
<div class="bg-scene">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <!-- Particules -->
    <?php for ($i = 0; $i < 20; $i++): ?>
        <div class="particle" style="
            left: <?= rand(0,100) ?>%;
            animation-duration: <?= rand(8,20) ?>s;
            animation-delay: -<?= rand(0,15) ?>s;
            width: <?= rand(2,4) ?>px;
            height: <?= rand(2,4) ?>px;
            opacity: <?= rand(3,7)/10 ?>;
        "></div>
    <?php endfor; ?>
</div>

<!-- Scène principale -->
<div class="login-scene">

    <!-- Côté gauche : présentation -->
    <div class="scene-left">
        <div class="app-tag">
            <i class="bi bi-bank2"></i>
            Système de supervision
        </div>
        <h1>Contrôlez chaque<br><span>transaction</span><br>en temps réel</h1>
        <p>Plateforme de supervision bancaire sécurisée. Gérez les versements, suivez les audits et maîtrisez les soldes de vos clients grâce aux triggers MySQL.</p>
        <div class="features-list">
            <div class="feature-item">
                <div class="feature-icon" style="background:rgba(37,99,235,0.15);color:#60a5fa;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <span>Authentification sécurisée avec protection CSRF</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background:rgba(79,70,229,0.15);color:#a78bfa;">
                    <i class="bi bi-activity"></i>
                </div>
                <span>Triggers MySQL automatiques sur chaque opération</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon" style="background:rgba(8,145,178,0.15);color:#67e8f9;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <span>Journal d'audit complet et filtrable</span>
            </div>
        </div>
    </div>

    <!-- Côté droit : formulaire -->
    <div class="scene-right">

        <div class="form-logo">
            <i class="bi bi-bank2"></i>
        </div>

        <div class="form-title">Bienvenue</div>
        <div class="form-subtitle">Connectez-vous pour accéder au portail de supervision</div>

        <!-- Message d'erreur -->
        <?php if (!empty($error)): ?>
            <div class="login-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?= e($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Comptes de démo -->
        <div class="demo-pill">
            <div>
                <div class="demo-pill-label">Admin</div>
                <code>admin</code> / <code>Admin123!</code>
            </div>
            <div class="demo-pill-sep"></div>
            <div>
                <div class="demo-pill-label">Utilisateur</div>
                <code>user1</code> / <code>User123!</code>
            </div>
        </div>

        <!-- Formulaire -->
        <form method="post" action="<?= BASE_URL ?>/?action=login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="field-group">
                <label for="username" class="field-label">Identifiant</label>
                <div class="field-wrap">
                    <i class="bi bi-person field-icon"></i>
                    <input type="text"
                           id="username"
                           name="username"
                           class="field-input"
                           placeholder="Entrez votre identifiant"
                           value="<?= e($_POST['username'] ?? '') ?>"
                           autocomplete="username"
                           required>
                </div>
            </div>

            <div class="field-group">
                <label for="password" class="field-label">Mot de passe</label>
                <div class="field-wrap">
                    <i class="bi bi-lock field-icon"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           class="field-input"
                           placeholder="••••••••••"
                           autocomplete="current-password"
                           required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-arrow-right-circle me-2"></i>Se connecter
            </button>
        </form>

        <div class="login-footer">
            <?= e(APP_NAME) ?> &mdash; v<?= e(APP_VERSION) ?> &mdash; <?= date('Y') ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
