<?php
/**
 * Gestion de la connexion PDO (pattern Singleton).
 *
 * @package Klaxon\Core
 */

declare(strict_types=1);

namespace Klaxon\Core;

use PDO;
use PDOException;

class Database
{
    /** @var PDO|null Instance unique */
    private static ?PDO $instance = null;

    /**
     * Constructeur privé — empêche l'instanciation directe.
     */
    private function __construct() {}

    /**
     * Retourne l'instance PDO unique.
     *
     * @return PDO
     * @throws PDOException Si la connexion échoue.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require ROOT . '/config/database.php';
            $dsn    = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );
            self::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }
}
