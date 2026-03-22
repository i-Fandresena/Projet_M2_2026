<?php
/**
 * views/versement/index.php — Interface CRUD Versements
 * Rôle : user
 * Variables injectées par VersementController::index()
 *   $versements, $clients, $currentPage, $totalPages, $totalItems, $search, $data
 */

// Extraction des variables du tableau $data
extract($data);

$pageTitle     = 'Versements — ' . APP_NAME;
$currentAction = 'versements';
?>

<?php require VIEWS_PATH . '/layout/header.php'; ?>
<?php require VIEWS_PATH . '/layout/navbar.php'; ?>

    <!-- Barre supérieure -->
    <div class="topbar">
        <div>
            <div class="topbar-title">
                <i class="bi bi-credit-card-2-front me-2" style="color:#2563EB;"></i>
                Gestion des Versements
            </div>
            <div class="topbar-meta">
                <?= $totalItems ?> versement<?= $totalItems > 1 ? 's' : '' ?> au total
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span style="font-size:0.75rem;color:#64748B;">
                <?= date('d/m/Y') ?>
            </span>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="page-content">

        <!-- Messages flash -->
        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success flash-message d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                <span><?= e($_SESSION['flash_success']) ?></span>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger flash-message d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                <span><?= e($_SESSION['flash_error']) ?></span>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <!-- Carte principale -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span class="card-header-title">
                    <i class="bi bi-table me-1"></i> Liste des versements
                </span>
                <div class="d-flex align-items-center gap-2">
                    <!-- Recherche -->
                    <form method="get" action="<?= BASE_URL ?>" class="d-flex gap-2">
                        <input type="hidden" name="action" value="versements">
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text" style="background:#F8FAFC;border-color:#E2E8F0;">
                                <i class="bi bi-search" style="font-size:0.75rem;color:#64748B;"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Rechercher..."
                                   value="<?= e($search) ?>">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary-custom">Filtrer</button>
                        <?php if ($search !== ''): ?>
                            <a href="<?= BASE_URL ?>?action=versements" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-x"></i>
                            </a>
                        <?php endif; ?>
                    </form>

                    <!-- Bouton ajout -->
                    <button type="button"
                            class="btn btn-sm btn-primary-custom"
                            data-bs-toggle="modal"
                            data-bs-target="#modalAjout">
                        <i class="bi bi-plus-lg me-1"></i> Nouveau versement
                    </button>
                </div>
            </div>

            <!-- Tableau des versements -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>N° Cheque</th>
                            <th>N° Compte</th>
                            <th>Client</th>
                            <th class="text-end">Montant (MAD)</th>
                            <th class="text-end">Solde Compte (MAD)</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($versements)): ?>
                            <tr>
                                <td colspan="7" class="text-center" style="color:#94A3B8;padding:2rem;">
                                    <i class="bi bi-inbox" style="font-size:1.5rem;display:block;margin-bottom:0.5rem;"></i>
                                    Aucun versement trouvé.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($versements as $v): ?>
                                <tr>
                                    <td style="color:#64748B;font-weight:500;"><?= e($v['numero_versement']) ?></td>
                                    <td>
                                        <span style="font-family:monospace;background:#F1F5F9;padding:0.15rem 0.4rem;border-radius:4px;font-size:0.75rem;">
                                            <?= e($v['numero_cheque']) ?>
                                        </span>
                                    </td>
                                    <td style="font-weight:500;"><?= e($v['numero_compte']) ?></td>
                                    <td><?= e($v['nom_client']) ?></td>
                                    <td class="text-end" style="font-weight:600;">
                                        <?= number_format((float)$v['montant'], 2, ',', ' ') ?>
                                    </td>
                                    <td class="text-end" style="font-weight:500;color:#16A34A;">
                                        <?= number_format((float)$v['solde'], 2, ',', ' ') ?>
                                    </td>
                                    <td class="text-center">
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-primary btn-edit"
                                                title="Modifier"
                                                data-id="<?= e($v['numero_versement']) ?>"
                                                data-cheque="<?= e($v['numero_cheque']) ?>"
                                                data-compte="<?= e($v['numero_compte']) ?>"
                                                data-montant="<?= e($v['montant']) ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm btn-icon btn-outline-danger btn-delete"
                                                title="Supprimer"
                                                data-id="<?= e($v['numero_versement']) ?>"
                                                data-cheque="<?= e($v['numero_cheque']) ?>">
                                            <i class="bi bi-trash3"></i>
                                        </button>
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
                                <!-- Précédent -->
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                       href="<?= BASE_URL ?>?action=versements&page=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>

                                <?php
                                // Afficher au maximum 5 pages autour de la page courante
                                $startPage = max(1, $currentPage - 2);
                                $endPage   = min($totalPages, $currentPage + 2);
                                for ($p = $startPage; $p <= $endPage; $p++):
                                ?>
                                    <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                        <a class="page-link"
                                           href="<?= BASE_URL ?>?action=versements&page=<?= $p ?>&search=<?= urlencode($search) ?>">
                                            <?= $p ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Suivant -->
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                       href="<?= BASE_URL ?>?action=versements&page=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div><!-- /.card -->
    </div><!-- /.page-content -->


<!-- ================================================
     MODAL : AJOUT DE VERSEMENT
================================================ -->
<div class="modal fade" id="modalAjout" tabindex="-1" aria-labelledby="modalAjoutLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAjoutLabel">
                    <i class="bi bi-plus-circle me-2"></i>Nouveau Versement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="post" action="<?= BASE_URL ?>?action=versement_store" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_cheque" class="form-label">Numero de cheque <span class="text-danger">*</span></label>
                        <input type="text"
                               id="add_cheque"
                               name="numero_cheque"
                               class="form-control"
                               placeholder="Ex: CHQ-2026-001"
                               maxlength="50"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="add_compte" class="form-label">Compte client <span class="text-danger">*</span></label>
                        <select id="add_compte" name="numero_compte" class="form-select" required>
                            <option value="">-- Selectionner un compte --</option>
                            <?php foreach ($clients as $c): ?>
                                <option value="<?= e($c['numero_compte']) ?>">
                                    <?= e($c['numero_compte']) ?> — <?= e($c['nom_client']) ?>
                                    (Solde : <?= number_format((float)$c['solde'], 2, ',', ' ') ?> MAD)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_montant" class="form-label">Montant (MAD) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number"
                                   id="add_montant"
                                   name="montant"
                                   class="form-control"
                                   placeholder="0.00"
                                   step="0.01"
                                   min="0.01"
                                   required>
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">
                        <i class="bi bi-save me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ================================================
     MODAL : MODIFICATION DE VERSEMENT
================================================ -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">
                    <i class="bi bi-pencil-square me-2"></i>Modifier le Versement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="post" action="<?= BASE_URL ?>?action=versement_update" novalidate>
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_cheque" class="form-label">Numero de cheque <span class="text-danger">*</span></label>
                        <input type="text"
                               id="edit_cheque"
                               name="numero_cheque"
                               class="form-control"
                               maxlength="50"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_compte" class="form-label">Compte client <span class="text-danger">*</span></label>
                        <select id="edit_compte" name="numero_compte" class="form-select" required>
                            <option value="">-- Selectionner un compte --</option>
                            <?php foreach ($clients as $c): ?>
                                <option value="<?= e($c['numero_compte']) ?>">
                                    <?= e($c['numero_compte']) ?> — <?= e($c['nom_client']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_montant" class="form-label">Montant (MAD) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number"
                                   id="edit_montant"
                                   name="montant"
                                   class="form-control"
                                   step="0.01"
                                   min="0.01"
                                   required>
                            <span class="input-group-text">MAD</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-primary-custom">
                        <i class="bi bi-save me-1"></i>Mettre a jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ================================================
     MODAL : CONFIRMATION SUPPRESSION
================================================ -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:#DC2626;">
                <h5 class="modal-title" id="modalDeleteLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmation
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p style="font-size:0.85rem;margin-bottom:0.25rem;">
                    Etes-vous certain de vouloir supprimer le versement :
                </p>
                <p class="fw-bold" id="delete_cheque_label" style="font-size:0.85rem;color:#DC2626;"></p>
                <p style="font-size:0.78rem;color:#64748B;margin:0;">
                    Cette action est irreversible. Le solde du compte sera automatiquement ajuste.
                </p>
            </div>
            <form method="post" action="<?= BASE_URL ?>?action=versement_delete">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash3 me-1"></i>Supprimer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Scripts spécifiques à cette vue -->
<?php ob_start(); ?>
<script>
(function () {
    'use strict';

    // --- Bouton Modifier : pré-remplissage du modal ---
    document.querySelectorAll('.btn-edit').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id      = this.dataset.id;
            var cheque  = this.dataset.cheque;
            var compte  = this.dataset.compte;
            var montant = this.dataset.montant;

            document.getElementById('edit_id').value      = id;
            document.getElementById('edit_cheque').value  = cheque;
            document.getElementById('edit_montant').value = montant;

            var select = document.getElementById('edit_compte');
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].value === compte) {
                    select.options[i].selected = true;
                    break;
                }
            }

            var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        });
    });

    // --- Bouton Supprimer : pré-remplissage du modal ---
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id     = this.dataset.id;
            var cheque = this.dataset.cheque;

            document.getElementById('delete_id').value        = id;
            document.getElementById('delete_cheque_label').textContent = 'Cheque N° ' + cheque;

            var modal = new bootstrap.Modal(document.getElementById('modalDelete'));
            modal.show();
        });
    });

    // --- Fermeture automatique des alertes flash après 4s ---
    setTimeout(function () {
        document.querySelectorAll('.flash-message').forEach(function (el) {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity    = '0';
            setTimeout(function () { el.remove(); }, 500);
        });
    }, 4000);

})();
</script>
<?php $extraScripts = ob_get_clean(); ?>

<?php require VIEWS_PATH . '/layout/footer.php'; ?>
