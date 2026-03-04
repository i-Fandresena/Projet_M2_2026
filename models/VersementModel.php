<?php
/**
 * VersementModel.php — Modèle CRUD des versements
 * Supervision Bancaire — Architecture MVC
 *
 * Toutes les opérations métier sur la table versement.
 * Les triggers MySQL s'occupent de l'audit et du solde automatiquement.
 */

require_once CONFIG_PATH . '/database.php';

class VersementModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // ----------------------------------------------------------------
    // LECTURE
    // ----------------------------------------------------------------

    /**
     * Retourne le nombre total de versements (avec filtre de recherche).
     *
     * @param string $search  Terme de recherche (numéro chèque ou nom client)
     * @return int
     */
    public function countAll(string $search = ''): int
    {
        if ($search !== '') {
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) 
                 FROM versement v
                 INNER JOIN client c ON v.numero_compte = c.numero_compte
                 WHERE v.numero_cheque LIKE :search
                    OR c.nom_client   LIKE :search2
                    OR CAST(v.numero_compte AS CHAR) LIKE :search3'
            );
            $like = '%' . $search . '%';
            $stmt->execute([':search' => $like, ':search2' => $like, ':search3' => $like]);
        } else {
            $stmt = $this->pdo->query('SELECT COUNT(*) FROM versement');
        }
        return (int) $stmt->fetchColumn();
    }

    /**
     * Retourne une page de versements avec pagination et recherche.
     *
     * @param int    $offset  Décalage (pour pagination)
     * @param int    $limit   Nombre de lignes par page
     * @param string $search  Terme de recherche
     * @return array
     */
    public function getPage(int $offset, int $limit, string $search = ''): array
    {
        if ($search !== '') {
            $stmt = $this->pdo->prepare(
                'SELECT v.numero_versement,
                        v.numero_cheque,
                        v.numero_compte,
                        c.nom_client,
                        v.montant,
                        c.solde
                 FROM versement v
                 INNER JOIN client c ON v.numero_compte = c.numero_compte
                 WHERE v.numero_cheque LIKE :search
                    OR c.nom_client   LIKE :search2
                    OR CAST(v.numero_compte AS CHAR) LIKE :search3
                 ORDER BY v.numero_versement DESC
                 LIMIT :limit OFFSET :offset'
            );
            $like = '%' . $search . '%';
            $stmt->bindValue(':search',  $like);
            $stmt->bindValue(':search2', $like);
            $stmt->bindValue(':search3', $like);
        } else {
            $stmt = $this->pdo->prepare(
                'SELECT v.numero_versement,
                        v.numero_cheque,
                        v.numero_compte,
                        c.nom_client,
                        v.montant,
                        c.solde
                 FROM versement v
                 INNER JOIN client c ON v.numero_compte = c.numero_compte
                 ORDER BY v.numero_versement DESC
                 LIMIT :limit OFFSET :offset'
            );
        }
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Recherche un versement par son identifiant.
     *
     * @param int $id
     * @return array|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT v.numero_versement,
                    v.numero_cheque,
                    v.numero_compte,
                    c.nom_client,
                    v.montant
             FROM versement v
             INNER JOIN client c ON v.numero_compte = c.numero_compte
             WHERE v.numero_versement = :id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // ----------------------------------------------------------------
    // ÉCRITURE
    // ----------------------------------------------------------------

    /**
     * Insère un nouveau versement.
     * Le trigger after_versement_insert s'exécutera automatiquement.
     *
     * @param string $numeroCheque
     * @param int    $numeroCompte
     * @param float  $montant
     * @return int  Identifiant inséré
     */
    public function insert(string $numeroCheque, int $numeroCompte, float $montant): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO versement (numero_cheque, numero_compte, montant)
             VALUES (:cheque, :compte, :montant)'
        );
        $stmt->execute([
            ':cheque'  => $numeroCheque,
            ':compte'  => $numeroCompte,
            ':montant' => $montant,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour un versement existant.
     * Le trigger after_versement_update s'exécutera automatiquement.
     *
     * @param int    $id
     * @param string $numeroCheque
     * @param int    $numeroCompte
     * @param float  $montant
     * @return bool
     */
    public function update(int $id, string $numeroCheque, int $numeroCompte, float $montant): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE versement
             SET    numero_cheque = :cheque,
                    numero_compte = :compte,
                    montant       = :montant
             WHERE  numero_versement = :id'
        );
        return $stmt->execute([
            ':cheque'  => $numeroCheque,
            ':compte'  => $numeroCompte,
            ':montant' => $montant,
            ':id'      => $id,
        ]);
    }

    /**
     * Supprime un versement.
     * Le trigger after_versement_delete s'exécutera automatiquement.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM versement WHERE numero_versement = :id'
        );
        return $stmt->execute([':id' => $id]);
    }
}
