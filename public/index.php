<?php

/**
 *point d'entrée unique de l'application "Touche Pas au Klaxon."
 *
 * @package klaxon
 */

declare(strict_types=1);

define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';

use Klaxon\Core\Router;

session_start();

$router = new Router();
$router->dispatch();
