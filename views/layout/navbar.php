<?php
/**
 * navbar.php — Barre latérale de navigation (sidebar)
 * Supervision Bancaire — Architecture MVC
 *
 * Requis : $_SESSION['user_username'] et $_SESSION['user_role'] disponibles.
 * $currentAction est défini dans index.php avant l'inclusion de la vue.
 */

$username = e($_SESSION['user_username'] ?? 'Utilisateur');
$role     = $_SESSION['user_role'] ?? 'user';
$initial  = strtoupper(substr($username, 0, 1));
?>

<!-- ================================================
     SIDEBAR
================================================ -->
<aside class="sidebar">

    <!-- Logo + nom de l'application -->
    <div class="sidebar-brand">
        <div class="sidebar-logo">
            <i class="bi bi-bank2 text-white" style="font-size:1.1rem;"></i>
        </div>
        <div>
            <div class="brand-name">Supervision</div>
            <div class="brand-sub">Bancaire</div>
        </div>
    </div>

    <!-- Navigation principale -->
    <nav class="sidebar-nav">
        <div class="nav-section-label">Navigation</div>

        <?php if ($role === 'user'): ?>
            <!-- Entrée Versements (role user) -->
            <a href="<?= BASE_URL ?>?action=versements"
               class="nav-item-link <?= ($currentAction === 'versements') ? 'active' : '' ?>">
                <i class="bi bi-credit-card-2-front"></i>
                Versements
            </a>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
            <!-- Entrée Audit (role admin) -->
            <a href="<?= BASE_URL ?>?action=audit"
               class="nav-item-link <?= ($currentAction === 'audit') ? 'active' : '' ?>">
                <i class="bi bi-shield-check"></i>
                Journal d'audit
            </a>
        <?php endif; ?>
    </nav>

    <!-- Pied de page : utilisateur connecté + déconnexion -->
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar"><?= $initial ?></div>
            <div>
                <div class="user-name"><?= $username ?></div>
                <div class="user-role"><?= e(ucfirst($role)) ?></div>
            </div>
        </div>
        <form method="post" action="<?= BASE_URL ?>?action=logout">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i> Deconnexion
            </button>
        </form>
    </div>

</aside>

<!-- ================================================
     CONTENEUR PRINCIPAL
================================================ -->
<div class="main-wrapper">
