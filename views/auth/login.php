<?php
/**
 * views/auth/login.php - Immersive blurred login page
 * Standalone (no header/navbar include)
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = $_SESSION['flash_error'] ?? null;
if ($error) {
    unset($_SESSION['flash_error']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - <?= e(APP_NAME) ?></title>

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
            position: relative;
            display: grid;
            place-items: center;
            background: #e9edf5;
        }

        .login-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(circle at 16% 20%, rgba(53,89,224,0.34), transparent 35%),
                radial-gradient(circle at 84% 14%, rgba(6,182,212,0.26), transparent 30%),
                radial-gradient(circle at 70% 82%, rgba(30,64,175,0.22), transparent 36%),
                linear-gradient(155deg, #eff3fa 0%, #e6ebf5 100%);
        }

        .blur-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(44px);
            opacity: 0.55;
            animation: drift 14s ease-in-out infinite;
        }

        .blur-orb.one {
            width: 360px;
            height: 360px;
            left: -80px;
            top: 18%;
            background: rgba(53,89,224,0.6);
        }

        .blur-orb.two {
            width: 300px;
            height: 300px;
            right: -60px;
            top: 12%;
            background: rgba(6,182,212,0.5);
            animation-delay: -4s;
        }

        .blur-orb.three {
            width: 430px;
            height: 430px;
            right: 12%;
            bottom: -140px;
            background: rgba(37,99,235,0.35);
            animation-delay: -8s;
        }

        @keyframes drift {
            0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
            50% { transform: translate3d(12px, -16px, 0) scale(1.07); }
        }

        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(51,65,85,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(51,65,85,0.07) 1px, transparent 1px);
            background-size: 54px 54px;
            mask-image: radial-gradient(circle at center, black 50%, transparent 95%);
            -webkit-mask-image: radial-gradient(circle at center, black 50%, transparent 95%);
        }

        .login-shell {
            width: min(1120px, 92vw);
            min-height: min(700px, 90vh);
            border-radius: 26px;
            overflow: hidden;
            border: 1px solid rgba(148,163,184,0.4);
            box-shadow: 0 30px 70px rgba(15, 23, 42, 0.18);
            background: rgba(255,255,255,0.56);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            position: relative;
            z-index: 10;
            display: grid;
            grid-template-columns: 1fr 430px;
            animation: shellEnter .45s ease;
        }

        @keyframes shellEnter {
            from { opacity: 0; transform: translateY(12px) scale(0.99); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .left-pane {
            padding: 3.2rem 3rem;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(145deg, rgba(255,255,255,0.7), rgba(255,255,255,0.35));
        }

        .scene-badge {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border: 1px solid rgba(53,89,224,0.3);
            border-radius: 999px;
            color: #1e3a8a;
            background: rgba(53,89,224,0.1);
            padding: 0.4rem 0.8rem;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .left-title {
            margin-top: 1.35rem;
            font-size: clamp(2rem, 3.2vw, 3rem);
            line-height: 1.06;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
            max-width: 560px;
        }

        .left-title .focus {
            background: linear-gradient(120deg, #1d4ed8, #0891b2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .left-sub {
            margin-top: 0.95rem;
            max-width: 520px;
            color: #334155;
            font-size: 0.92rem;
            line-height: 1.7;
        }

        .principles {
            margin-top: 1.7rem;
            display: grid;
            gap: 0.7rem;
            max-width: 520px;
        }

        .principle {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            color: #334155;
            font-size: 0.82rem;
            font-weight: 500;
        }

        .principle-mark {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(53,89,224,0.2);
            background: rgba(53,89,224,0.1);
            color: #1d4ed8;
            flex-shrink: 0;
        }

        .metric-row {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.6rem;
        }

        .metric {
            border: 1px solid rgba(148,163,184,0.35);
            border-radius: 12px;
            background: rgba(255,255,255,0.58);
            padding: 0.72rem 0.78rem;
        }

        .metric-value {
            color: #0f172a;
            font-size: 1.22rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .metric-label {
            color: #64748b;
            font-size: 0.64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 0.18rem;
        }

        .right-pane {
            background: rgba(248,250,252,0.66);
            border-left: 1px solid rgba(148,163,184,0.32);
            padding: 2.7rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .brand-lockup {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            margin-bottom: 2rem;
        }

        .logo-cube {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(140deg, #3559e0, #06b6d4);
            box-shadow: 0 12px 20px rgba(53,89,224,0.3);
        }

        .brand-title {
            color: #0f172a;
            font-size: 0.95rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .brand-caption {
            color: #64748b;
            font-size: 0.66rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 1px;
        }

        .form-title {
            color: #0f172a;
            font-size: 1.48rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.3rem;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 0.8rem;
            margin-bottom: 1.55rem;
        }

        .form-error {
            border: 1px solid rgba(185,28,28,0.28);
            background: rgba(185,28,28,0.08);
            color: #991b1b;
            border-radius: 10px;
            padding: 0.68rem 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.79rem;
            margin-bottom: 0.95rem;
            animation: errorIn .2s ease;
        }

        @keyframes errorIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .field {
            margin-bottom: 0.9rem;
        }

        .field-label {
            display: block;
            color: #475569;
            font-size: 0.69rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.44rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 0.78rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
            pointer-events: none;
            transition: color .15s;
        }

        .field-input {
            width: 100%;
            border: 1px solid rgba(148,163,184,0.45);
            border-radius: 11px;
            background: rgba(255,255,255,0.9);
            color: #0f172a;
            font-family: 'Inter', sans-serif;
            font-size: 0.87rem;
            padding: 0.77rem 0.8rem 0.77rem 2.45rem;
            outline: none;
            transition: border-color .16s, box-shadow .16s, background .16s;
        }

        .field-input::placeholder {
            color: #94a3b8;
        }

        .field-wrap:focus-within .field-icon {
            color: #1d4ed8;
        }

        .field-input:focus {
            border-color: rgba(53,89,224,0.42);
            box-shadow: 0 0 0 4px rgba(53,89,224,0.11);
            background: #fff;
        }

        .btn-signin {
            margin-top: 0.45rem;
            width: 100%;
            border: none;
            border-radius: 11px;
            padding: 0.86rem;
            background: linear-gradient(135deg, #3559e0, #0ea5e9);
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            box-shadow: 0 15px 24px rgba(53,89,224,0.3);
            cursor: pointer;
            transition: transform .16s, box-shadow .16s;
            position: relative;
            overflow: hidden;
        }

        .btn-signin::after {
            content: '';
            position: absolute;
            top: 0;
            left: -130%;
            width: 110%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.22), transparent);
            transition: left .45s ease;
        }

        .btn-signin:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(53,89,224,0.36);
        }

        .btn-signin:hover::after {
            left: 130%;
        }

        .security-note {
            margin-top: 1rem;
            color: #64748b;
            font-size: 0.71rem;
            display: flex;
            align-items: center;
            gap: 0.42rem;
        }

        @media (max-width: 920px) {
            .login-shell {
                grid-template-columns: 1fr;
                width: min(530px, 92vw);
                min-height: auto;
            }

            .left-pane {
                display: none;
            }

            .right-pane {
                border-left: none;
                padding: 2.2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-bg" aria-hidden="true">
        <div class="blur-orb one"></div>
        <div class="blur-orb two"></div>
        <div class="blur-orb three"></div>
        <div class="grid-overlay"></div>
    </div>

    <main class="login-shell" role="main">
        <section class="left-pane">
            <div>
                <div class="scene-badge">
                    <i class="bi bi-diagram-3"></i>
                    Plateforme de supervision
                </div>

                <h1 class="left-title">
                    Contrôler les <span class="focus">versements bancaires</span> avec audit automatique par triggers.
                </h1>

                <p class="left-sub">
                    Interface orientee exploitation pour suivre les operations d'ajout, de modification et de suppression,
                    avec mise a jour immediate des soldes clients.
                </p>

                <div class="principles">
                    <div class="principle">
                        <span class="principle-mark"><i class="bi bi-shield-check"></i></span>
                        Journal d'audit complet des actions utilisateur
                    </div>
                    <div class="principle">
                        <span class="principle-mark"><i class="bi bi-arrow-repeat"></i></span>
                        Reconciliation dynamique des montants et soldes
                    </div>
                    <div class="principle">
                        <span class="principle-mark"><i class="bi bi-graph-up-arrow"></i></span>
                        Vue statistique des insertions, mises a jour et suppressions
                    </div>
                </div>
            </div>

            <div class="metric-row">
                <div class="metric">
                    <div class="metric-value">3</div>
                    <div class="metric-label">Triggers actifs</div>
                </div>
                <div class="metric">
                    <div class="metric-value">I/U/D</div>
                    <div class="metric-label">Traçabilite</div>
                </div>
                <div class="metric">
                    <div class="metric-value">100%</div>
                    <div class="metric-label">Auditabilite</div>
                </div>
            </div>
        </section>

        <section class="right-pane">
            <div class="brand-lockup">
                <div class="logo-cube" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L20 6.5V17.5L12 22L4 17.5V6.5L12 2Z" stroke="currentColor" stroke-width="1.6"/>
                        <path d="M8 10.2L12 7.8L16 10.2V13.8L12 16.2L8 13.8V10.2Z" stroke="currentColor" stroke-width="1.6"/>
                    </svg>
                </div>
                <div>
                    <div class="brand-title">AstraVerse Audit</div>
                    <div class="brand-caption">Supervision des versements</div>
                </div>
            </div>

            <h2 class="form-title">Connexion securisee</h2>
            <p class="form-subtitle">Accedez au suivi des versements et au journal d'audit.</p>

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
                               placeholder="Mot de passe"
                               autocomplete="current-password"
                               required>
                    </div>
                </div>

                <button type="submit" class="btn-signin">Se connecter</button>
            </form>

            <div class="security-note">
                <i class="bi bi-shield-lock"></i>
                Session protegee, verification CSRF active.
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
</body>
</html>
