<?php

/**
 * Modèle gérant les trajets de covoiturage.
 *
 * @package Klaxon\Model
 */

declare(strict_types=1);

namespace Klaxon\Model;

use Klaxon\Core\Model;

class TrajetModel extends Model
{
    /**
     * Retourne les trajets disponibles (places > 0, départ futur), triés par date de départ.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAvailable(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT t.*, 
                ad.nom AS agence_depart,
                aa.nom AS agence_arrivee,
                u.nom AS user_nom,
                u.prenom AS user_prenom,
                u.telephone AS user_telephone,
                u.email AS user_email
         FROM trajet t
         JOIN agence ad ON ad.id = t.agence_depart_id
         JOIN agence aa ON aa.id = t.agence_arrivee_id
         JOIN utilisateur u ON u.id = t.utilisateur_id
         WHERE t.nb_places_dispo > 0
           AND t.gdh_depart > NOW()
         ORDER BY t.gdh_depart ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne tous les trajets (admin), triés par date de départ.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT t.*,
                    ad.nom AS agence_depart,
                    aa.nom AS agence_arrivee,
                    u.nom AS user_nom, u.prenom AS user_prenom
             FROM trajet t
             JOIN agence ad ON ad.id = t.agence_depart_id
             JOIN agence aa ON aa.id = t.agence_arrivee_id
             JOIN utilisateur u ON u.id = t.utilisateur_id
             ORDER BY t.gdh_depart ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne un trajet complet avec infos utilisateur par son id.
     *
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT t.*,
                    ad.nom AS agence_depart,
                    aa.nom AS agence_arrivee,
                    u.nom AS user_nom, u.prenom AS user_prenom,
                    u.email AS user_email, u.telephone AS user_telephone
             FROM trajet t
             JOIN agence ad ON ad.id = t.agence_depart_id
             JOIN agence aa ON aa.id = t.agence_arrivee_id
             JOIN utilisateur u ON u.id = t.utilisateur_id
             WHERE t.id = :id'
        );
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Crée un nouveau trajet en base.
     *
     * @param array<string, mixed> $data
     * @return int Id du trajet créé.
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO trajet
                (utilisateur_id, agence_depart_id, agence_arrivee_id,
                 gdh_depart, gdh_arrivee, nb_places_total, nb_places_dispo)
             VALUES
                (:utilisateur_id, :agence_depart_id, :agence_arrivee_id,
                 :gdh_depart, :gdh_arrivee, :nb_places_total, :nb_places_total)'
        );
        $stmt->execute([
            ':utilisateur_id'    => $data['utilisateur_id'],
            ':agence_depart_id'  => $data['agence_depart_id'],
            ':agence_arrivee_id' => $data['agence_arrivee_id'],
            ':gdh_depart'        => $data['gdh_depart'],
            ':gdh_arrivee'       => $data['gdh_arrivee'],
            ':nb_places_total'   => $data['nb_places_total'],
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Met à jour un trajet existant.
     *
     * @param int                  $id
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE trajet
             SET agence_depart_id  = :agence_depart_id,
                 agence_arrivee_id = :agence_arrivee_id,
                 gdh_depart        = :gdh_depart,
                 gdh_arrivee       = :gdh_arrivee,
                 nb_places_total   = :nb_places_total,
                 nb_places_dispo   = :nb_places_dispo
             WHERE id = :id'
        );
        $stmt->execute([
            ':agence_depart_id'  => $data['agence_depart_id'],
            ':agence_arrivee_id' => $data['agence_arrivee_id'],
            ':gdh_depart'        => $data['gdh_depart'],
            ':gdh_arrivee'       => $data['gdh_arrivee'],
            ':nb_places_total'   => $data['nb_places_total'],
            ':nb_places_dispo'   => $data['nb_places_dispo'],
            ':id'                => $id,
        ]);
    }

    /**
     * Supprime un trajet par son id.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM trajet WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
