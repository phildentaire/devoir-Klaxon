<?php

/**
 *contrôleur gérant les agences (réservé à l'administrateur).
 *
 * @package Klaxon\Controller
 */

declare(strict_types=1);

namespace Klaxon\Controller;

use Klaxon\Core\Controller;
use Klaxon\Model\AgenceModel;

class AgenceController extends Controller
{
    /**
     *liste toutes les agences.
     */
    public function index(): void
    {
        $this->requireAdmin();
        $agences = (new AgenceModel())->findAll();
        $this->render('agence/index', compact('agences'));
    }

    /**
     *formulaire de création d'une agence.
     */
    public function create(): void
    {
        $this->requireAdmin();
        $this->render('agence/form', ['agence' => null, 'error' => null]);
    }

    /**
     * Enregistre une nouvelle agence.
     */
    public function store(): void
    {
        $this->requireAdmin();
        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            $this->render('agence/form', ['agence' => null, 'error' => 'Le nom est obligatoire.']);
            return;
        }

        (new AgenceModel())->create($nom);
        $this->redirect('/admin/agences');
    }

    /**
     * Formulaire de modification d'une agence.
     *
     * @param int $id
     */
    public function edit(int $id): void
    {
        $this->requireAdmin();
        $agence = (new AgenceModel())->findById($id);
        $this->render('agence/form', ['agence' => $agence, 'error' => null]);
    }

    /**
     * Met à jour une agence.
     *
     * @param int $id
     */
    public function update(int $id): void
    {
        $this->requireAdmin();
        $nom = trim($_POST['nom'] ?? '');

        if ($nom === '') {
            $agence = (new AgenceModel())->findById($id);
            $this->render('agence/form', ['agence' => $agence, 'error' => 'Le nom est obligatoire.']);
            return;
        }

        (new AgenceModel())->update($id, $nom);
        $this->redirect('/admin/agences');
    }

    /**
     * Supprime une agence.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->requireAdmin();
        (new AgenceModel())->delete($id);
        $this->redirect('/admin/agences');
    }
}
