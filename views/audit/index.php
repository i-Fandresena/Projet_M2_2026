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
        <div>
            <div class="topbar-title">
                <i class="bi bi-shield-check me-2" style="color:#2563EB;"></i>
                Journal d'Audit
            </div>
            <div class="topbar-meta">
                Historique complet des operations &mdash; lecture seule
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:0.75rem;color:#64748B;"><?= date('d/m/Y') ?></span>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="page-content">

        <!-- Cartes statistiques -->
        <div class="row g-3 mb-4">

            <!-- Total INSERT -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(22,163,74,0.1);">
                        <i class="bi bi-plus-circle-fill" style="color:#16A34A;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:#16A34A;">
                            <?= number_format($stats['INSERT']) ?>
                        </div>
                        <div class="stat-label">Insertions</div>
                    </div>
                </div>
            </div>

            <!-- Total UPDATE -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(37,99,235,0.1);">
                        <i class="bi bi-arrow-repeat" style="color:#2563EB;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:#2563EB;">
                            <?= number_format($stats['UPDATE']) ?>
                        </div>
                        <div class="stat-label">Modifications</div>
                    </div>
                </div>
            </div>

            <!-- Total DELETE -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(220,38,38,0.1);">
                        <i class="bi bi-trash3-fill" style="color:#DC2626;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:#DC2626;">
                            <?= number_format($stats['DELETE']) ?>
                        </div>
                        <div class="stat-label">Suppressions</div>
                    </div>
                </div>
            </div>

            <!-- Total général -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:rgba(30,41,59,0.08);">
                        <i class="bi bi-list-columns-reverse" style="color:#1E293B;"></i>
                    </div>
                    <div>
                        <div class="stat-value" style="color:#1E293B;">
                            <?= number_format($stats['INSERT'] + $stats['UPDATE'] + $stats['DELETE']) ?>
                        </div>
                        <div class="stat-label">Total operations</div>
                    </div>
                </div>
            </div>

        </div><!-- /.row statistiques -->

        <!-- Carte tableau d'audit -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="card-header-title">
                    <i class="bi bi-table me-1"></i> Historique des operations
                </span>

                <!-- Filtre par type d'action -->
                <form method="get" action="<?= BASE_URL ?>" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="action" value="audit">
                    <label for="filter_type" style="font-size:0.75rem;color:#64748B;white-space:nowrap;" class="mb-0">
                        Filtrer :
                    </label>
                    <select id="filter_type" name="type" class="form-select form-select-sm" style="width:160px;">
                        <option value=""         <?= $typeAction === ''       ? 'selected' : '' ?>>Toutes les actions</option>
                        <option value="INSERT"   <?= $typeAction === 'INSERT' ? 'selected' : '' ?>>Insertions</option>
                        <option value="UPDATE"   <?= $typeAction === 'UPDATE' ? 'selected' : '' ?>>Modifications</option>
                        <option value="DELETE"   <?= $typeAction === 'DELETE' ? 'selected' : '' ?>>Suppressions</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary-custom">Appliquer</button>
                    <?php if ($typeAction !== ''): ?>
                        <a href="<?= BASE_URL ?>?action=audit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Tableau -->
            <div class="table-responsive">
                <table class="table">
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
                                    <td style="color:#64748B;font-weight:500;"><?= e($a['id']) ?></td>
                                    <td>
                                        <?php
                                        $badgeClass = match ($a['type_action']) {
                                            'INSERT' => 'badge-insert',
                                            'UPDATE' => 'badge-update',
                                            'DELETE' => 'badge-delete',
                                            default  => 'badge-insert',
                                        };
                                        ?>
                                        <span class="<?= $badgeClass ?>"><?= e($a['type_action']) ?></span>
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
                                        <span style="font-size:0.75rem;background:#F1F5F9;padding:0.1rem 0.4rem;border-radius:4px;">
                                            <?= e($a['utilisateur'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td style="font-size:0.72rem;color:#64748B;">
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
                <div class="card-body border-top" style="padding:0.75rem 1.25rem;">
                    <div class="d-flex align-items-center justify-content-between">
                        <span style="font-size:0.75rem;color:#64748B;">
                            Page <?= $currentPage ?> / <?= $totalPages ?>
                            &mdash; <?= $totalItems ?> enregistrement<?= $totalItems > 1 ? 's' : '' ?>
                        </span>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                       href="<?= BASE_URL ?>?action=audit&page=<?= $currentPage - 1 ?>&type=<?= urlencode($typeAction) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>

                                <?php
                                $startPage = max(1, $currentPage - 2);
                                $endPage   = min($totalPages, $currentPage + 2);
                                for ($p = $startPage; $p <= $endPage; $p++):
                                ?>
                                    <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link"
                                           href="<?= BASE_URL ?>?action=audit&page=<?= $p ?>&type=<?= urlencode($typeAction) ?>">
                                            <?= $p ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                       href="<?= BASE_URL ?>?action=audit&page=<?= $currentPage + 1 ?>&type=<?= urlencode($typeAction) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Pied de tableau : récapitulatif statistiques -->
            <div class="card-body border-top" style="background:#F8FAFC;border-radius:0 0 10px 10px;padding:0.875rem 1.25rem;">
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <span style="font-size:0.72rem;color:#64748B;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">
                        Synthese globale
                    </span>
                    <span style="font-size:0.8rem;">
                        <span class="badge-insert me-1"><?= number_format($stats['INSERT']) ?></span>
                        insertion<?= $stats['INSERT'] > 1 ? 's' : '' ?>
                    </span>
                    <span style="font-size:0.8rem;">
                        <span class="badge-update me-1"><?= number_format($stats['UPDATE']) ?></span>
                        modification<?= $stats['UPDATE'] > 1 ? 's' : '' ?>
                    </span>
                    <span style="font-size:0.8rem;">
                        <span class="badge-delete me-1"><?= number_format($stats['DELETE']) ?></span>
                        suppression<?= $stats['DELETE'] > 1 ? 's' : '' ?>
                    </span>
                </div>
            </div>

        </div><!-- /.card -->
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
