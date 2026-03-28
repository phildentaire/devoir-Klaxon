<?php

/**
 *modèle gérant les agences (villes).
 *
 * @package Klaxon\Model
 */

declare(strict_types=1);

namespace Klaxon\Model;

use Klaxon\Core\Model;

class AgenceModel extends Model
{
    /**
     *retourne toutes les agences triées par nom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT * FROM agence ORDER BY nom ASC')->fetchAll();
    }

    /**
     *retourne une agence par son id.
     *
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM agence WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     *crée une agence.
     *
     * @param string $nom
     * @return int Id de l'agence créée.
     */
    public function create(string $nom): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO agence (nom) VALUES (:nom)');
        $stmt->execute([':nom' => $nom]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     *met à jour le nom d'une agence.
     *
     * @param int    $id
     * @param string $nom
     */
    public function update(int $id, string $nom): void
    {
        $stmt = $this->pdo->prepare('UPDATE agence SET nom = :nom WHERE id = :id');
        $stmt->execute([':nom' => $nom, ':id' => $id]);
    }

    /**
     *supprime une agence.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM agence WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
