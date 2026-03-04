<?php
/**
 * ClientModel.php — Modèle de gestion des clients bancaires
 * Supervision Bancaire — Architecture MVC
 */

require_once CONFIG_PATH . '/database.php';

class ClientModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Retourne tous les clients (pour les listes déroulantes).
     *
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT numero_compte, nom_client, solde FROM client ORDER BY nom_client ASC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Recherche un client par son numéro de compte.
     *
     * @param int $numeroCompte
     * @return array|false
     */
    public function findByNumeroCompte(int $numeroCompte): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT numero_compte, nom_client, solde FROM client WHERE numero_compte = :numero_compte LIMIT 1'
        );
        $stmt->execute([':numero_compte' => $numeroCompte]);
        return $stmt->fetch();
    }
}
