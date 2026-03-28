<?php

/**
 *contrôleur du tableau de bord administrateur.
 *
 * @package Klaxon\Controller
 */

declare(strict_types=1);

namespace Klaxon\Controller;

use Klaxon\Core\Controller;
use Klaxon\Model\TrajetModel;
use Klaxon\Model\AgenceModel;
use Klaxon\Model\UtilisateurModel;

class AdminController extends Controller
{
    /**
     *tableau de bord administrateur.
     */
    public function dashboard(): void
    {
        $this->requireAdmin();
        $this->render('admin/dashboard', []);
    }

    /**
     *liste tous les utilisateurs.
     */
    public function utilisateurs(): void
    {
        $this->requireAdmin();
        $utilisateurs = (new UtilisateurModel())->findAll();
        $this->render('admin/utilisateurs', compact('utilisateurs'));
    }

    /**
     *liste tous les trajets (admin).
     */
    public function trajets(): void
    {
        $this->requireAdmin();
        $trajets = (new TrajetModel())->findAll();
        $this->render('admin/trajets', compact('trajets'));
    }

    /**
     *supprime un trajet (admin).
     *
     * @param int $id
     */
    public function deleteTrajet(int $id): void
    {
        $this->requireAdmin();
        (new TrajetModel())->delete($id);
        $this->redirect('/admin/trajets');
    }
}
