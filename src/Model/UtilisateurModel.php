<?php
/**
 * Modèle gérant les utilisateurs.
 *
 * @package Klaxon\Model
 */

declare(strict_types=1);

namespace Klaxon\Model;

use Klaxon\Core\Model;

class UtilisateurModel extends Model
{
    /**
     * Retourne tous les utilisateurs.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT id, nom, prenom, email, telephone, est_admin FROM utilisateur ORDER BY nom, prenom')->fetchAll();
    }

    /**
     * Recherche un utilisateur par email.
     *
     * @param string $email
     * @return array<string, mixed>|null
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM utilisateur WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
