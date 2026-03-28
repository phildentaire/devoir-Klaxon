<?php
/**
 * Contrôleur gérant les trajets de covoiturage.
 *
 * @package Klaxon\Controller
 */

declare(strict_types=1);

namespace Klaxon\Controller;

use Klaxon\Core\Controller;
use Klaxon\Model\TrajetModel;
use Klaxon\Model\AgenceModel;

class TrajetController extends Controller
{
    /**
     * Page d'accueil — liste des trajets disponibles.
     */
    public function index(): void
    {
        $trajetModel = new TrajetModel();
        $trajets     = $trajetModel->findAvailable();

        $this->render('trajet/index', compact('trajets'));
    }

    /**
     * Formulaire de création d'un trajet.
     */
    public function create(): void
    {
        $this->requireAuth();
        $agences = (new AgenceModel())->findAll();
        $this->render('trajet/form', ['trajet' => null, 'agences' => $agences, 'errors' => []]);
    }

    /**
     * Enregistre un nouveau trajet.
     */
    public function store(): void
    {
        $this->requireAuth();
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $agences = (new AgenceModel())->findAll();
            $this->render('trajet/form', ['trajet' => null, 'agences' => $agences, 'errors' => $errors]);
            return;
        }

        $model = new TrajetModel();
        $model->create([
            'utilisateur_id'    => $_SESSION['user']['id'],
            'agence_depart_id'  => (int)$_POST['agence_depart_id'],
            'agence_arrivee_id' => (int)$_POST['agence_arrivee_id'],
            'gdh_depart'        => $_POST['gdh_depart'],
            'gdh_arrivee'       => $_POST['gdh_arrivee'],
            'nb_places_total'   => (int)$_POST['nb_places_total'],
        ]);

        $this->redirect('/');
    }

    /**
     * Formulaire de modification d'un trajet.
     *
     * @param int $id
     */
    public function edit(int $id): void
    {
        $this->requireAuth();
        $model  = new TrajetModel();
        $trajet = $model->findById($id);

        if (!$trajet || (!$_SESSION['user']['est_admin'] && $trajet['utilisateur_id'] !== $_SESSION['user']['id'])) {
            $this->redirect('/');
        }

        $agences = (new AgenceModel())->findAll();
        $this->render('trajet/form', ['trajet' => $trajet, 'agences' => $agences, 'errors' => []]);
    }

    /**
     * Met à jour un trajet existant.
     *
     * @param int $id
     */
    public function update(int $id): void
    {
        $this->requireAuth();
        $model  = new TrajetModel();
        $trajet = $model->findById($id);

        if (!$trajet || (!$_SESSION['user']['est_admin'] && $trajet['utilisateur_id'] !== $_SESSION['user']['id'])) {
            $this->redirect('/');
        }

        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $agences = (new AgenceModel())->findAll();
            $this->render('trajet/form', ['trajet' => $trajet, 'agences' => $agences, 'errors' => $errors]);
            return;
        }

        $model->update($id, [
            'agence_depart_id'  => (int)$_POST['agence_depart_id'],
            'agence_arrivee_id' => (int)$_POST['agence_arrivee_id'],
            'gdh_depart'        => $_POST['gdh_depart'],
            'gdh_arrivee'       => $_POST['gdh_arrivee'],
            'nb_places_total'   => (int)$_POST['nb_places_total'],
            'nb_places_dispo'   => (int)$_POST['nb_places_dispo'],
        ]);

        $this->redirect('/');
    }

    /**
     * Supprime un trajet.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->requireAuth();
        $model  = new TrajetModel();
        $trajet = $model->findById($id);

        if ($trajet && ($_SESSION['user']['est_admin'] || $trajet['utilisateur_id'] === $_SESSION['user']['id'])) {
            $model->delete($id);
        }

        $this->redirect('/');
    }

    /**
     * Valide les données du formulaire trajet.
     *
     * @param array<string, mixed> $data
     * @return array<string, string> Tableau d'erreurs (vide si tout est valide).
     */
    private function validate(array $data): array
    {
        $errors = [];

        $departId  = (int)($data['agence_depart_id']  ?? 0);
        $arriveeId = (int)($data['agence_arrivee_id'] ?? 0);
        $depart    = $data['gdh_depart']  ?? '';
        $arrivee   = $data['gdh_arrivee'] ?? '';
        $places    = (int)($data['nb_places_total'] ?? 0);

        if ($departId === $arriveeId) {
            $errors['agences'] = "L'agence de départ et d'arrivée doivent être différentes.";
        }
        if ($depart >= $arrivee) {
            $errors['dates'] = "La date d'arrivée doit être postérieure à la date de départ.";
        }
        if ($depart <= date('Y-m-d\TH:i')) {
            $errors['depart'] = "La date de départ doit être dans le futur.";
        }
        if ($places < 1) {
            $errors['places'] = "Le nombre de places doit être au moins 1.";
        }

        return $errors;
    }
}
