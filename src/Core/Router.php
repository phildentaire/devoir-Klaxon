<?php

/**
 *routeur principal de l'application.
 *dispatche les requêtes vers les contrôleurs appropriés.
 *
 * @package Klaxon\Core
 */

declare(strict_types=1);

namespace Klaxon\Core;

use Klaxon\Controller\TrajetController;
use Klaxon\Controller\AuthController;
use Klaxon\Controller\AgenceController;
use Klaxon\Controller\AdminController;

class Router
{
    /**
     *dispatche la requête HTTP vers le bon contrôleur/action.
     */
    public function dispatch(): void
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        //retire le sous-répertoire éventuel
        $base = dirname($_SERVER['SCRIPT_NAME']);
        if ($base !== '/' && $base !== '\\') {
            $uri = substr($uri, strlen($base));
        }
        $uri = '/' . ltrim($uri, '/');
        match (true) {
            //accueil
            $uri === '/' && $method === 'GET'
            => (new TrajetController())->index(),

            //auth
            $uri === '/connexion' && $method === 'GET'
            => (new AuthController())->showLogin(),
            $uri === '/connexion' && $method === 'POST'
            => (new AuthController())->login(),
            $uri === '/deconnexion'
            => (new AuthController())->logout(),

            //trajets 
            $uri === '/trajets/creer' && $method === 'GET'
            => (new TrajetController())->create(),
            $uri === '/trajets/creer' && $method === 'POST'
            => (new TrajetController())->store(),
            preg_match('#^/trajets/(\d+)/modifier$#', $uri, $m) && $method === 'GET'
            => (new TrajetController())->edit((int)$m[1]),
            preg_match('#^/trajets/(\d+)/modifier$#', $uri, $m) && $method === 'POST'
            => (new TrajetController())->update((int)$m[1]),
            preg_match('#^/trajets/(\d+)/supprimer$#', $uri, $m) && $method === 'POST'
            => (new TrajetController())->delete((int)$m[1]),

            //admin
            $uri === '/admin' && $method === 'GET'
            => (new AdminController())->dashboard(),
            $uri === '/admin/utilisateurs'
            => (new AdminController())->utilisateurs(),
            $uri === '/admin/trajets'
            => (new AdminController())->trajets(),
            preg_match('#^/admin/trajets/(\d+)/supprimer$#', $uri, $m) && $method === 'POST'
            => (new AdminController())->deleteTrajet((int)$m[1]),

            //agences (admin) 
            $uri === '/admin/agences' && $method === 'GET'
            => (new AgenceController())->index(),
            $uri === '/admin/agences/creer' && $method === 'GET'
            => (new AgenceController())->create(),
            $uri === '/admin/agences/creer' && $method === 'POST'
            => (new AgenceController())->store(),
            preg_match('#^/admin/agences/(\d+)/modifier$#', $uri, $m) && $method === 'GET'
            => (new AgenceController())->edit((int)$m[1]),
            preg_match('#^/admin/agences/(\d+)/modifier$#', $uri, $m) && $method === 'POST'
            => (new AgenceController())->update((int)$m[1]),
            preg_match('#^/admin/agences/(\d+)/supprimer$#', $uri, $m) && $method === 'POST'
            => (new AgenceController())->delete((int)$m[1]),

            default => $this->notFound(),
        };
    }

    /**
     * affiche une page 404.
     */
    private function notFound(): void
    {
        http_response_code(404);
        echo '<h1>404 — Page introuvable</h1>';
    }
}
