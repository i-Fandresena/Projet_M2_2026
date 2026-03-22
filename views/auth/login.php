<?php
/**
 * views/auth/login.php — Page de connexion immersive
 * Standalone (sans header.php / navbar.php)
 */
if (session_status() === PHP_SESSION_NONE) session_start();
$error = $_SESSION['flash_error'] ?? null;
if ($error) unset($_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — <?= e(APP_NAME) ?></title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            height: 100vh; width: 100vw;
            overflow: hidden;
            display: flex;
            background: #09071a;
        }

        /* ═══════════ LEFT — ART PANEL ═══════════ */
        .art-panel {
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Perspective grid */
        .art-panel::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(139,92,246,.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139,92,246,.07) 1px, transparent 1px);
            background-size: 48px 48px;
            transform: perspective(600px) rotateX(10deg) scale(1.1);
            transform-origin: top center;
        }

        /* Ambient radial glow */
        .art-panel::after {
            content: '';
            position: absolute;
            width: 700px; height: 700px;
            left: 50%; top: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(139,92,246,.1) 0%, rgba(45,212,191,.05) 40%, transparent 70%);
            pointer-events: none;
        }

        /* Constellation dots */
        .constellation { position: absolute; inset: 0; z-index: 1; }
        .star {
            position: absolute;
            background: rgba(167,139,250,.55);
            border-radius: 50%;
            animation: twinkle ease-in-out infinite;
        }
        @keyframes twinkle {
            0%, 100% { opacity: .15; transform: scale(1); }
            50%       { opacity: .7;  transform: scale(1.6); }
        }

        /* Center composition */
        .art-center {
            position: relative; z-index: 2;
            text-align: center;
            user-select: none;
        }

        /* Orbital system */
        .orbital {
            width: 200px; height: 200px;
            position: relative;
            margin: 0 auto 2.5rem;
        }

        .ring {
            position: absolute;
            border-radius: 50%;
            border: 1px solid transparent;
        }

        .ring-outer {
            inset: 0;
            border-color: rgba(139,92,246,.22);
            animation: orbit 9s linear infinite;
        }
        .ring-outer::before {
            content: '';
            position: absolute;
            top: -5px; left: 50%;
            width: 10px; height: 10px;
            background: #8b5cf6;
            border-radius: 50%;
            box-shadow: 0 0 14px 3px rgba(139,92,246,.6);
            transform: translateX(-50%);
        }

        .ring-mid {
            inset: 24px;
            border-color: rgba(45,212,191,.18);
            animation: orbit 6s linear infinite reverse;
        }
        .ring-mid::before {
            content: '';
            position: absolute;
            bottom: -4px; left: 50%;
            width: 7px; height: 7px;
            background: #2dd4bf;
            border-radius: 50%;
            box-shadow: 0 0 10px 2px rgba(45,212,191,.55);
            transform: translateX(-50%);
        }

        .ring-inner {
            inset: 50px;
            border-color: rgba(251,191,36,.12);
            animation: orbit 12s linear infinite;
        }
        .ring-inner::before {
            content: '';
            position: absolute;
            top: -3px; right: 12px;
            width: 5px; height: 5px;
            background: #fbbf24;
            border-radius: 50%;
            box-shadow: 0 0 8px 2px rgba(251,191,36,.5);
        }

        @keyframes orbit {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Core icon */
        .orbital-core {
            position: absolute;
            inset: 66px;
            border-radius: 50%;
            background: radial-gradient(135deg, rgba(124,58,237,.3), rgba(45,212,191,.15));
            border: 1px solid rgba(139,92,246,.3);
            display: flex; align-items: center; justify-content: center;
        }
        .orbital-core .bi {
            font-size: 2rem;
            background: linear-gradient(135deg, #c4b5fd, #2dd4bf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .art-title {
            font-size: 2rem;
            font-weight: 800;
            color: #ede9fe;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 0.6rem;
        }
        .art-title em {
            font-style: normal;
            background: linear-gradient(90deg, #a78bfa 20%, #2dd4bf 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .art-sub {
            font-size: 0.78rem;
            color: #514e6a;
            line-height: 1.65;
            max-width: 300px;
            margin: 0 auto;
        }

        /* ═══════════ RIGHT — FORM PANEL ═══════════ */
        .form-panel {
            width: 430px;
            flex-shrink: 0;
            background: #100d1e;
            border-left: 1px solid rgba(139,92,246,.12);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 2.25rem;
            position: relative;
            overflow: hidden;
        }

        /* Top gradient line */
        .form-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #7c3aed 30%, #2dd4bf 70%, transparent 100%);
        }

        /* Subtle background glow */
        .form-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            right: -80px; bottom: -80px;
            background: radial-gradient(circle, rgba(139,92,246,.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .form-logo {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #7c3aed, #2dd4bf);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 1.75rem;
            box-shadow: 0 4px 16px rgba(124,58,237,.38);
            position: relative; z-index: 1;
        }

        .form-heading {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ede9fe;
            letter-spacing: -0.02em;
            margin-bottom: 0.35rem;
            position: relative; z-index: 1;
        }

        .form-sub {
            font-size: 0.77rem;
            color: #514e6a;
            margin-bottom: 1.875rem;
            position: relative; z-index: 1;
        }

        /* Error */
        .form-error {
            background: rgba(251,113,133,.08);
            border: 1px solid rgba(251,113,133,.22);
            border-radius: 7px;
            color: #fda4af;
            font-size: 0.77rem;
            padding: 0.65rem 0.875rem;
            margin-bottom: 1.125rem;
            display: flex; align-items: center; gap: 0.5rem;
            animation: popIn .2s ease;
            position: relative; z-index: 1;
        }
        @keyframes popIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Fields */
        .field { margin-bottom: 0.875rem; position: relative; z-index: 1; }

        .field-label {
            display: block;
            font-size: 0.71rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #9d9abc;
            margin-bottom: 0.4rem;
        }

        .field-wrap { position: relative; }

        .field-icon {
            position: absolute;
            left: 0.875rem; top: 50%;
            transform: translateY(-50%);
            color: #514e6a;
            font-size: 0.88rem;
            pointer-events: none;
            transition: color .15s;
        }

        .field-input {
            width: 100%;
            background: rgba(255,255,255,.038);
            border: 1px solid rgba(139,92,246,.1);
            border-radius: 8px;
            color: #ede9fe;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            padding: 0.775rem 0.875rem 0.775rem 2.75rem;
            outline: none;
            transition: all .15s;
        }

        .field-input::placeholder { color: #3a3857; }

        .field-input:focus {
            background: rgba(139,92,246,.07);
            border-color: rgba(139,92,246,.38);
            box-shadow: 0 0 0 3px rgba(139,92,246,.09);
        }

        .field-wrap:focus-within .field-icon { color: #a78bfa; }

        /* Submit */
        .btn-signin {
            width: 100%;
            background: linear-gradient(130deg, #7c3aed 0%, #4338ca 55%, #1d4ed8 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 700;
            padding: 0.875rem;
            cursor: pointer;
            margin-top: 0.375rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(124,58,237,.38);
            letter-spacing: 0.01em;
            transition: box-shadow .2s, transform .2s;
            z-index: 1;
        }

        .btn-signin::after {
            content: '';
            position: absolute;
            top: 0; left: -120%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
            transition: left .45s;
        }

        .btn-signin:hover {
            box-shadow: 0 6px 28px rgba(124,58,237,.52);
            transform: translateY(-1px);
        }
        .btn-signin:hover::after { left: 120%; }

        /* Demo credentials */
        .demo-section {
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(139,92,246,.08);
            position: relative; z-index: 1;
        }

        .demo-label {
            font-size: 0.61rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #3a3857;
            text-align: center;
            margin-bottom: 0.75rem;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .demo-card {
            background: rgba(255,255,255,.02);
            border: 1px solid rgba(139,92,246,.08);
            border-radius: 7px;
            padding: 0.625rem 0.75rem;
        }

        .demo-role {
            font-size: 0.59rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.3rem;
        }

        .demo-card.is-admin .demo-role { color: #a78bfa; }
        .demo-card.is-user  .demo-role { color: #2dd4bf; }

        .demo-cred {
            font-size: 0.71rem;
            color: #6e6a8a;
            line-height: 1.5;
            font-variant-numeric: tabular-nums;
        }

        /* Responsive */
        @media (max-width: 820px) {
            .art-panel { display: none; }
            .form-panel { width: 100%; }
        }
    </style>
</head>
<body>

    <!-- ART PANEL (left) -->
    <div class="art-panel">
        <div class="constellation" id="constellation"></div>

        <div class="art-center">
            <!-- Orbital animation -->
            <div class="orbital">
                <div class="ring ring-outer"></div>
                <div class="ring ring-mid"></div>
                <div class="ring ring-inner"></div>
                <div class="orbital-core">
                    <i class="bi bi-bank2"></i>
                </div>
            </div>

            <h1 class="art-title">Supervision<br><em>Bancaire</em></h1>
            <p class="art-sub">
                Surveillance des opérations en temps réel.<br>
                Audit automatisé via triggers MySQL.
            </p>
        </div>
    </div>

    <!-- FORM PANEL (right) -->
    <div class="form-panel">

        <div class="form-logo">
            <i class="bi bi-bank2"></i>
        </div>

        <h2 class="form-heading">Connexion</h2>
        <p class="form-sub">Accès sécurisé à la plateforme</p>

        <?php if ($error): ?>
            <div class="form-error">
                <i class="bi bi-exclamation-circle"></i>
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>?action=login" novalidate>
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

            <div class="field">
                <label class="field-label" for="username">Identifiant</label>
                <div class="field-wrap">
                    <i class="bi bi-person field-icon"></i>
                    <input type="text"
                           id="username"
                           name="username"
                           class="field-input"
                           placeholder="Nom d'utilisateur"
                           autocomplete="username"
                           required
                           value="<?= e($_POST['username'] ?? '') ?>">
                </div>
            </div>

            <div class="field">
                <label class="field-label" for="password">Mot de passe</label>
                <div class="field-wrap">
                    <i class="bi bi-lock field-icon"></i>
                    <input type="password"
                           id="password"
                           name="password"
                           class="field-input"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           required>
                </div>
            </div>

            <button type="submit" class="btn-signin">Se connecter</button>
        </form>

        <div class="demo-section">
            <div class="demo-label">Accès de démonstration</div>
            <div class="demo-grid">
                <div class="demo-card is-admin">
                    <div class="demo-role">Admin</div>
                    <div class="demo-cred">admin<br>Admin123!</div>
                </div>
                <div class="demo-card is-user">
                    <div class="demo-role">Utilisateur</div>
                    <div class="demo-cred">user1<br>User123!</div>
                </div>
            </div>
        </div>

    </div>

    <script>
    (function () {
        // Generate constellation stars
        var c = document.getElementById('constellation');
        if (!c) return;
        for (var i = 0; i < 30; i++) {
            var s = document.createElement('div');
            s.className = 'star';
            var sz = Math.random() * 2.5 + 1;
            s.style.cssText =
                'width:' + sz + 'px;height:' + sz + 'px;' +
                'left:' + (Math.random() * 100) + '%;' +
                'top:'  + (Math.random() * 100) + '%;' +
                'animation-duration:' + (2.5 + Math.random() * 4) + 's;' +
                'animation-delay:-'   + (Math.random() * 5) + 's;';
            c.appendChild(s);
        }
    })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
</body>
</html>
