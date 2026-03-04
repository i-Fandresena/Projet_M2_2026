<?php
/**
 * VersementController.php — CRUD des versements (rôle : user)
 * Supervision Bancaire — Architecture MVC
 *
 * Toutes les méthodes vérifient que l'utilisateur est authentifié
 * et possède le rôle 'user'.
 */

require_once MODELS_PATH . '/VersementModel.php';
require_once MODELS_PATH . '/ClientModel.php';

class VersementController
{
    private VersementModel $versementModel;
    private ClientModel    $clientModel;

    public function __construct()
    {
        $this->versementModel = new VersementModel();
        $this->clientModel    = new ClientModel();
    }

    // ----------------------------------------------------------------
    // Affichage principal : tableau paginé + recherche
    // ----------------------------------------------------------------
    public function index(): void
    {
        requireAuth();

        $search      = trim($_GET['search'] ?? '');
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        $perPage     = ITEMS_PER_PAGE;
        $offset      = ($currentPage - 1) * $perPage;

        $totalItems  = $this->versementModel->countAll($search);
        $totalPages  = (int) ceil($totalItems / $perPage);
        $versements  = $this->versementModel->getPage($offset, $perPage, $search);
        $clients     = $this->clientModel->getAll();

        // Données transmises à la vue
        $data = compact('versements', 'clients', 'currentPage', 'totalPages', 'totalItems', 'search');

        require VIEWS_PATH . '/versement/index.php';
    }

    // ----------------------------------------------------------------
    // Ajout d'un versement (POST)
    // ----------------------------------------------------------------
    public function store(): void
    {
        requireAuth();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashError('Token de sécurité invalide.');
            redirect('?action=versements');
        }

        $errors = $this->validateVersementInput($_POST);

        if (!empty($errors)) {
            $this->flashError(implode(' ', $errors));
            redirect('?action=versements');
        }

        $numeroCheque = trim($_POST['numero_cheque']);
        $numeroCompte = (int) $_POST['numero_compte'];
        $montant      = (float) $_POST['montant'];

        // Vérifier que le compte existe
        if (!$this->clientModel->findByNumeroCompte($numeroCompte)) {
            $this->flashError('Numéro de compte introuvable.');
            redirect('?action=versements');
        }

        // Définir @app_user pour le trigger MySQL
        Database::setAppUser($_SESSION['user_username'] ?? 'inconnu');

        $this->versementModel->insert($numeroCheque, $numeroCompte, $montant);

        $_SESSION['flash_success'] = 'Versement ajouté avec succès.';
        redirect('?action=versements');
    }

    // ----------------------------------------------------------------
    // Modification d'un versement (POST)
    // ----------------------------------------------------------------
    public function update(): void
    {
        requireAuth();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashError('Token de sécurité invalide.');
            redirect('?action=versements');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->versementModel->findById($id)) {
            $this->flashError('Versement introuvable.');
            redirect('?action=versements');
        }

        $errors = $this->validateVersementInput($_POST);

        if (!empty($errors)) {
            $this->flashError(implode(' ', $errors));
            redirect('?action=versements');
        }

        $numeroCheque = trim($_POST['numero_cheque']);
        $numeroCompte = (int) $_POST['numero_compte'];
        $montant      = (float) $_POST['montant'];

        if (!$this->clientModel->findByNumeroCompte($numeroCompte)) {
            $this->flashError('Numéro de compte introuvable.');
            redirect('?action=versements');
        }

        // Définir @app_user pour le trigger MySQL
        Database::setAppUser($_SESSION['user_username'] ?? 'inconnu');

        $this->versementModel->update($id, $numeroCheque, $numeroCompte, $montant);

        $_SESSION['flash_success'] = 'Versement modifié avec succès.';
        redirect('?action=versements');
    }

    // ----------------------------------------------------------------
    // Suppression d'un versement (POST)
    // ----------------------------------------------------------------
    public function destroy(): void
    {
        requireAuth();

        if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->flashError('Token de sécurité invalide.');
            redirect('?action=versements');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->versementModel->findById($id)) {
            $this->flashError('Versement introuvable.');
            redirect('?action=versements');
        }

        // Définir @app_user pour le trigger MySQL
        Database::setAppUser($_SESSION['user_username'] ?? 'inconnu');

        $this->versementModel->delete($id);

        $_SESSION['flash_success'] = 'Versement supprimé avec succès.';
        redirect('?action=versements');
    }

    // ----------------------------------------------------------------
    // Validation des entrées versement
    // ----------------------------------------------------------------
    private function validateVersementInput(array $post): array
    {
        $errors = [];

        $numeroCheque = trim($post['numero_cheque'] ?? '');
        $numeroCompte = trim($post['numero_compte'] ?? '');
        $montant      = trim($post['montant'] ?? '');

        if ($numeroCheque === '') {
            $errors[] = 'Le numéro de chèque est obligatoire.';
        } elseif (strlen($numeroCheque) > 50) {
            $errors[] = 'Le numéro de chèque ne doit pas dépasser 50 caractères.';
        }

        if ($numeroCompte === '' || !ctype_digit($numeroCompte)) {
            $errors[] = 'Le numéro de compte est invalide.';
        }

        if ($montant === '' || !is_numeric($montant) || (float) $montant <= 0) {
            $errors[] = 'Le montant doit être un nombre positif.';
        }

        return $errors;
    }

    // ----------------------------------------------------------------
    // Message flash d'erreur
    // ----------------------------------------------------------------
    private function flashError(string $message): void
    {
        $_SESSION['flash_error'] = $message;
    }
}
