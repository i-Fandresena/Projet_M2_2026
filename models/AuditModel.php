<?php
/**
 * AuditModel.php — Modèle lecture du journal d'audit
 * Supervision Bancaire — Architecture MVC
 *
 * Ce modèle est en lecture seule.
 * Les données sont insérées exclusivement par les triggers MySQL.
 */

require_once CONFIG_PATH . '/database.php';

class AuditModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // ----------------------------------------------------------------
    // LECTURE PAGINÉE AVEC FILTRES
    // ----------------------------------------------------------------

    /**
     * Retourne le nombre total d'enregistrements d'audit
     * selon un éventuel filtre de type d'action.
     *
     * @param string $typeAction  '' | 'INSERT' | 'UPDATE' | 'DELETE'
     * @return int
     */
    public function countAll(string $typeAction = ''): int
    {
        if ($typeAction !== '') {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) FROM audit_versement WHERE type_action = :type'
            );
            $stmt->execute([':type' => $typeAction]);
        } else {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM audit_versement');
        }
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retourne une page d'enregistrements d'audit triés par date descendante.
     *
     * @param int    $offset
     * @param int    $limit
     * @param string $typeAction  Filtre optionnel
     * @return array
     */
    public function getPage(int $offset, int $limit, string $typeAction = ''): array
    {
        if ($typeAction !== '') {
            $stmt = $this->pdo->prepare(
                'SELECT id,
                        type_action,
                        date_operation,
                        numero_versement,
                        numero_compte,
                        nom_client,
                        montant_ancien,
                        montant_nouveau,
                        utilisateur,
                        machine_hote
                 FROM audit_versement
                 WHERE type_action = :type
                 ORDER BY date_operation DESC
                 LIMIT :limit OFFSET :offset'
            );
            $stmt->bindValue(':type',   $typeAction);
        } else {
            $stmt = $this->pdo->prepare(
                'SELECT id,
                        type_action,
                        date_operation,
                        numero_versement,
                        numero_compte,
                        nom_client,
                        montant_ancien,
                        montant_nouveau,
                        utilisateur,
                        machine_hote
                 FROM audit_versement
                 ORDER BY date_operation DESC
                 LIMIT :limit OFFSET :offset'
            );
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ----------------------------------------------------------------
    // STATISTIQUES
    // ----------------------------------------------------------------

    /**
     * Calcule dynamiquement les statistiques d'audit par type d'action.
     * Retourne un tableau : ['INSERT' => n, 'UPDATE' => n, 'DELETE' => n]
     *
     * @return array<string,int>
     */
    public function getStats(): array
    {
        $stmt = $this->pdo->query(
            'SELECT type_action, COUNT(*) AS total
             FROM audit_versement
             GROUP BY type_action'
        );
        $rows = $stmt->fetchAll();

        $stats = [
            'INSERT' => 0,
            'UPDATE' => 0,
            'DELETE' => 0,
        ];

        foreach ($rows as $row) {
            if (isset($stats[$row['type_action']])) {
                $stats[$row['type_action']] = (int) $row['total'];
            }
        }

        return $stats;
    }
}
