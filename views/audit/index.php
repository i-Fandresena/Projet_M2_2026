<?php
/**
 * views/audit/index.php — Journal d'audit (lecture seule, rôle admin)
 * Variables injectées par AuditController::index()
 *   $audits, $currentPage, $totalPages, $totalItems, $typeAction, $stats, $data
 */

extract($data);

$pageTitle     = 'Journal d\'audit — ' . APP_NAME;
$currentAction = 'audit';
?>

<?php require VIEWS_PATH . '/layout/header.php'; ?>
<?php require VIEWS_PATH . '/layout/navbar.php'; ?>

    <!-- Barre supérieure -->
    <div class="topbar">
        <div class="topbar-title">
            <div class="title-icon"><i class="bi bi-shield-check"></i></div>
            <div>
                Journal d'Audit
                <div class="topbar-meta">Historique complet des operations &mdash; lecture seule</div>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-chip">
                <i class="bi bi-calendar3"></i>
                <?= date('d/m/Y') ?>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="page-content">

        <!-- Cartes statistiques -->
        <div class="stat-grid">

            <!-- Total INSERT -->
            <div class="stat-card accent2">
                <div class="stat-top">
                    <div>
                        <div class="stat-value"><?= number_format($stats['INSERT']) ?></div>
                        <div class="stat-label">Insertions</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(16,185,129,0.12);color:#34d399;">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Total UPDATE -->
            <div class="stat-card accent1">
                <div class="stat-top">
                    <div>
                        <div class="stat-value"><?= number_format($stats['UPDATE']) ?></div>
                        <div class="stat-label">Modifications</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(59,130,246,0.12);color:#60a5fa;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
            </div>

            <!-- Total DELETE -->
            <div class="stat-card accent4">
                <div class="stat-top">
                    <div>
                        <div class="stat-value"><?= number_format($stats['DELETE']) ?></div>
                        <div class="stat-label">Suppressions</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(239,68,68,0.12);color:#f87171;">
                        <i class="bi bi-trash3-fill"></i>
                    </div>
                </div>
            </div>

            <!-- Total général -->
            <div class="stat-card accent3">
                <div class="stat-top">
                    <div>
                        <div class="stat-value"><?= number_format($stats['INSERT'] + $stats['UPDATE'] + $stats['DELETE']) ?></div>
                        <div class="stat-label">Total operations</div>
                    </div>
                    <div class="stat-icon" style="background:rgba(245,158,11,0.12);color:#fbbf24;">
                        <i class="bi bi-list-columns-reverse"></i>
                    </div>
                </div>
            </div>

        </div><!-- /.stat-grid -->

        <!-- Carte tableau d'audit -->
        <div class="main-card">
            <div class="main-card-header">
                <span class="main-card-title">
                    <i class="bi bi-table"></i> Historique des operations
                </span>

                <!-- Filtre par type d'action -->
                <form method="get" action="<?= BASE_URL ?>" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="action" value="audit">
                    <label for="filter_type" style="font-size:0.75rem;color:var(--c-muted);white-space:nowrap;" class="mb-0">
                        Filtrer :
                    </label>
                    <select id="filter_type" name="type" class="filter-select">
                        <option value=""         <?= $typeAction === ''       ? 'selected' : '' ?>>Toutes les actions</option>
                        <option value="INSERT"   <?= $typeAction === 'INSERT' ? 'selected' : '' ?>>Insertions</option>
                        <option value="UPDATE"   <?= $typeAction === 'UPDATE' ? 'selected' : '' ?>>Modifications</option>
                        <option value="DELETE"   <?= $typeAction === 'DELETE' ? 'selected' : '' ?>>Suppressions</option>
                    </select>
                    <button type="submit" class="btn-filter">Appliquer</button>
                    <?php if ($typeAction !== ''): ?>
                        <a href="<?= BASE_URL ?>?action=audit" class="btn-clear">
                            <i class="bi bi-x"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Date operation</th>
                            <th>N° Versement</th>
                            <th>N° Compte</th>
                            <th>Client</th>
                            <th class="text-end">Montant Anc. (MAD)</th>
                            <th class="text-end">Montant Nouv. (MAD)</th>
                            <th>Utilisateur</th>
                            <th>Hote</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($audits)): ?>
                            <tr>
                                <td colspan="10" class="text-center" style="color:#94A3B8;padding:2rem;">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;"></i>
                                    Aucune operation enregistree.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($audits as $a): ?>
                                <tr>
                                    <td style="color:var(--c-muted);font-weight:500;"><?= e($a['id']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match ($a['type_action']) {
                                            'INSERT' => 'badge-insert',
                                            'UPDATE' => 'badge-update',
                                            'DELETE' => 'badge-delete',
                                            default  => 'badge-insert',
                                        };
                                        ?>
                                        <span class="badge-action <?= $badgeClass ?>"><?= e($a['type_action']) ?></span>
                                    </td>
                                    <td style="white-space:nowrap;font-size:0.75rem;">
                                        <?= e(date('d/m/Y H:i:s', strtotime($a['date_operation']))) ?>
                                    </td>
                                    <td style="font-weight:500;"><?= e($a['numero_versement'] ?? '-') ?></td>
                                    <td><?= e($a['numero_compte'] ?? '-') ?></td>
                                    <td><?= e($a['nom_client'] ?? '-') ?></td>
                                    <td class="text-end">
                                        <?php if ($a['montant_ancien'] !== null && $a['montant_ancien'] != 0): ?>
                                            <?= number_format((float)$a['montant_ancien'], 2, ',', ' ') ?>
                                        <?php else: ?>
                                            <span style="color:#94A3B8;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end" style="font-weight:500;">
                                        <?php if ($a['montant_nouveau'] !== null && $a['montant_nouveau'] != 0): ?>
                                            <?= number_format((float)$a['montant_nouveau'], 2, ',', ' ') ?>
                                        <?php else: ?>
                                            <span style="color:#94A3B8;">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span style="font-size:0.75rem;background:rgba(255,255,255,0.06);padding:0.1rem 0.4rem;border-radius:4px;color:var(--c-muted2);">
                                            <?= e($a['utilisateur'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td style="font-size:0.72rem;color:var(--c-muted);">
                                        <?= e($a['machine_hote'] ?? '-') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="table-footer">
                    <span class="table-footer-meta">
                        Page <?= $currentPage ?> / <?= $totalPages ?>
                        &mdash; <?= $totalItems ?> enregistrement<?= $totalItems > 1 ? 's' : '' ?>
                    </span>
                    <div class="pager">
                        <a class="pager-btn <?= $currentPage <= 1 ? 'disabled' : '' ?>"
                           href="<?= BASE_URL ?>?action=audit&page=<?= $currentPage - 1 ?>&type=<?= urlencode($typeAction) ?>"
                           <?= $currentPage <= 1 ? 'aria-disabled="true"' : '' ?>>
                            <i class="bi bi-chevron-left"></i>
                        </a>

                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage   = min($totalPages, $currentPage + 2);
                        for ($p = $startPage; $p <= $endPage; $p++):
                        ?>
                            <a class="pager-btn <?= $p === $currentPage ? 'active' : '' ?>"
                               href="<?= BASE_URL ?>?action=audit&page=<?= $p ?>&type=<?= urlencode($typeAction) ?>">
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>

                        <a class="pager-btn <?= $currentPage >= $totalPages ? 'disabled' : '' ?>"
                           href="<?= BASE_URL ?>?action=audit&page=<?= $currentPage + 1 ?>&type=<?= urlencode($typeAction) ?>"
                           <?= $currentPage >= $totalPages ? 'aria-disabled="true"' : '' ?>>
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Pied de tableau -->
            <div class="table-footer">
                <span class="table-footer-meta" style="font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Synthèse globale</span>
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <span style="font-size:0.8rem;">
                        <span class="badge-action badge-insert me-1"><?= number_format($stats['INSERT']) ?></span>
                        insertion<?= $stats['INSERT'] > 1 ? 's' : '' ?>
                    </span>
                    <span style="font-size:0.8rem;">
                        <span class="badge-action badge-update me-1"><?= number_format($stats['UPDATE']) ?></span>
                        modification<?= $stats['UPDATE'] > 1 ? 's' : '' ?>
                    </span>
                    <span style="font-size:0.8rem;">
                        <span class="badge-action badge-delete me-1"><?= number_format($stats['DELETE']) ?></span>
                        suppression<?= $stats['DELETE'] > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>

        </div><!-- /.main-card -->
    </div><!-- /.page-content -->

<!-- Script : auto-submit du filtre au changement de sélection -->
<?php ob_start(); ?>
<script>
(function () {
    'use strict';
    var sel = document.getElementById('filter_type');
    if (sel) {
        sel.addEventListener('change', function () {
            this.form.submit();
        });
    }
})();
</script>
<?php $extraScripts = ob_get_clean(); ?>

<?php require VIEWS_PATH . '/layout/footer.php'; ?>
