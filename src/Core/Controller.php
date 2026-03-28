<?php
/**
 * Contrôleur de base dont héritent tous les contrôleurs.
 *
 * @package Klaxon\Core
 */

declare(strict_types=1);

namespace Klaxon\Core;

abstract class Controller
{
    /**
     * Affiche une vue en lui passant des données.
     *
     * @param string               $view Chemin relatif de la vue (ex: 'trajet/index')
     * @param array<string, mixed> $data Variables à extraire dans la vue
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = ROOT . '/src/View/' . $view . '.php';

        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        require ROOT . '/src/View/layout/main.php';
    }

    /**
     * Redirige vers une URL.
     *
     * @param string $url URL cible
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Vérifie que l'utilisateur est connecté, redirige sinon.
     */
    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('/connexion');
        }
    }

    /**
     * Vérifie que l'utilisateur est administrateur, redirige sinon.
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (empty($_SESSION['user']['est_admin'])) {
            $this->redirect('/');
        }
    }
}
