<?php
/**
 * database.php — Connexion PDO à MySQL
 * Supervision Bancaire — Architecture MVC
 *
 * Utilisation :  $pdo = Database::getConnection();
 */

class Database
{
    // ------------------------------------------------------------
    // Paramètres de connexion — À adapter selon votre XAMPP
    // ------------------------------------------------------------
    private const DB_HOST    = '127.0.0.1';
    private const DB_PORT    = '3306';
    private const DB_NAME    = 'supervision_bancaire';
    private const DB_USER    = 'root';
    private const DB_PASS    = '';          // Mot de passe XAMPP (vide par défaut)
    private const DB_CHARSET = 'utf8mb4';

    /** Instance singleton PDO */
    private static ?PDO $instance = null;

    /** Constructeur privé — singleton */
    private function __construct() {}

    /**
     * Retourne l'instance PDO unique (singleton).
     * Lance une exception propre en cas d'échec de connexion.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                self::DB_HOST,
                self::DB_PORT,
                self::DB_NAME,
                self::DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_FOUND_ROWS   => true,
            ];

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            } catch (PDOException $e) {
                // Ne jamais exposer les détails de connexion en production
                if (defined('APP_ENV') && APP_ENV === 'development') {
                    throw new RuntimeException('Connexion base de données : ' . $e->getMessage(), 500);
                }
                throw new RuntimeException('Erreur de connexion à la base de données.', 500);
            }
        }

        return self::$instance;
    }

    /**
     * Définit la variable de session MySQL @app_user
     * pour que les triggers puissent la lire.
     */
    public static function setAppUser(string $username): void
    {
        $pdo  = self::getConnection();
        $stmt = $pdo->prepare('SET @app_user = :username');
        $stmt->execute([':username' => $username]);
    }
}
