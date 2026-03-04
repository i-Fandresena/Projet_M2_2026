<?php
/**
 * AuditController.php — Journal d'audit (rôle : admin, lecture seule)
 * Supervision Bancaire — Architecture MVC
 */

require_once MODELS_PATH . '/AuditModel.php';

class AuditController
{
    private AuditModel $auditModel;

    public function __construct()
    {
        $this->auditModel = new AuditModel();
    }

    // ----------------------------------------------------------------
    // Affichage du journal d'audit paginé et filtrable
    // ----------------------------------------------------------------
    public function index(): void
    {
        requireRole('admin');

        // Filtre par type d'action
        $allowedTypes = ['', 'INSERT', 'UPDATE', 'DELETE'];
        $typeAction   = $_GET['type'] ?? '';
        if (!in_array($typeAction, $allowedTypes, true)) {
            $typeAction = '';
        }

        // Pagination
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $perPage     = ITEMS_PER_PAGE;
        $offset      = ($currentPage - 1) * $perPage;

        $totalItems  = $this->auditModel->countAll($typeAction);
        $totalPages  = (int) ceil($totalItems / $perPage);
        $audits      = $this->auditModel->getPage($offset, $perPage, $typeAction);

        // Statistiques par type d'action (requêtes COUNT GROUP BY)
        $stats = $this->auditModel->getStats();

        $data = compact('audits', 'currentPage', 'totalPages', 'totalItems', 'typeAction', 'stats');

        require VIEWS_PATH . '/audit/index.php';
    }
}
