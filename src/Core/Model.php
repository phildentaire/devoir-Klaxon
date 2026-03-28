<?php
/**
 * Modèle de base dont héritent tous les modèles.
 *
 * @package Klaxon\Core
 */

declare(strict_types=1);

namespace Klaxon\Core;

use PDO;

abstract class Model
{
    /** @var PDO Connexion PDO partagée */
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
}
