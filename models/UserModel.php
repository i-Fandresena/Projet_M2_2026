<?php
/**
 * UserModel.php — Modèle de gestion des utilisateurs
 * Supervision Bancaire — Architecture MVC
 */

require_once CONFIG_PATH . '/database.php';

class UserModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Recherche un utilisateur par son nom d'utilisateur.
     *
     * @param string $username
     * @return array|false  Tableau assoc ou false si introuvable
     */
    public function findByUsername(string $username): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, username, password, role FROM users WHERE username = :username LIMIT 1'
        );
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    /**
     * Vérifie les identifiants et retourne l'utilisateur si valides.
     *
     * @param string $username
     * @param string $password  Mot de passe en clair
     * @return array|false
     */
    public function authenticate(string $username, string $password): array|false
    {
        $user = $this->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
