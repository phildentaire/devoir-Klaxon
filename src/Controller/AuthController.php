<?php

/**
 *contrôleur gérant l'authentification (connexion / déconnexion).
 *
 * @package Klaxon\Controller
 */

declare(strict_types=1);

namespace Klaxon\Controller;

use Klaxon\Core\Controller;
use Klaxon\Model\UtilisateurModel;

class AuthController extends Controller
{
    /**
     *affiche le formulaire de connexion.
     */
    public function showLogin(): void
    {
        $this->render('auth/login', ['error' => null]);
    }

    /**
     *traite le formulaire de connexion.
     */
    public function login(): void
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $model = new UtilisateurModel();
        $user  = $model->findByEmail($email);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user'] = [
                'id'        => $user['id'],
                'nom'       => $user['nom'],
                'prenom'    => $user['prenom'],
                'email'     => $user['email'],
                'telephone' => $user['telephone'],
                'est_admin' => (bool)$user['est_admin'],
            ];
            $this->redirect($user['est_admin'] ? '/admin' : '/');
        }

        $this->render('auth/login', ['error' => 'Identifiants incorrects.']);
    }

    /**
     *déconnecte l'utilisateur et détruit la session.
     */
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/');
    }
}
