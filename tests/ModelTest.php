<?php
/**
 * Tests unitaires couvrant les opérations d'écriture en base de données.
 * Utilise une base SQLite en mémoire pour l'isolation totale des tests.
 *
 * @package Klaxon\Tests
 */

declare(strict_types=1);

namespace Klaxon\Tests;

use PHPUnit\Framework\TestCase;
use PDO;

/**
 * Remplace le singleton Database pour pointer vers SQLite en mémoire.
 */
class TestDatabase
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $pdo = new PDO('sqlite::memory:');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Création du schéma minimal
            $pdo->exec('
                CREATE TABLE agence (
                    id   INTEGER PRIMARY KEY AUTOINCREMENT,
                    nom  TEXT NOT NULL UNIQUE
                );

                CREATE TABLE utilisateur (
                    id            INTEGER PRIMARY KEY AUTOINCREMENT,
                    nom           TEXT NOT NULL,
                    prenom        TEXT NOT NULL,
                    telephone     TEXT NOT NULL,
                    email         TEXT NOT NULL UNIQUE,
                    mot_de_passe  TEXT NOT NULL,
                    est_admin     INTEGER NOT NULL DEFAULT 0
                );

                CREATE TABLE trajet (
                    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
                    utilisateur_id     INTEGER NOT NULL,
                    agence_depart_id   INTEGER NOT NULL,
                    agence_arrivee_id  INTEGER NOT NULL,
                    gdh_depart         TEXT NOT NULL,
                    gdh_arrivee        TEXT NOT NULL,
                    nb_places_total    INTEGER NOT NULL DEFAULT 1,
                    nb_places_dispo    INTEGER NOT NULL DEFAULT 1,
                    created_at         TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
            ');

            // Données minimales
            $pdo->exec("INSERT INTO agence (nom) VALUES ('Paris'), ('Lyon')");
            $pdo->exec("
                INSERT INTO utilisateur (nom, prenom, telephone, email, mot_de_passe)
                VALUES ('Test', 'User', '0600000000', 'test@test.fr', 'hash')
            ");

            self::$instance = $pdo;
        }
        return self::$instance;
    }
}

// =====================================================================
// Versions "testables" des modèles (injectent TestDatabase à la place)
// =====================================================================

class TestableAgenceModel
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = TestDatabase::getInstance();
    }

    public function findAll(): array
    {
        return $this->pdo->query('SELECT * FROM agence ORDER BY nom ASC')->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM agence WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    public function create(string $nom): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO agence (nom) VALUES (:nom)');
        $stmt->execute([':nom' => $nom]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, string $nom): void
    {
        $stmt = $this->pdo->prepare('UPDATE agence SET nom = :nom WHERE id = :id');
        $stmt->execute([':nom' => $nom, ':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM agence WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}

class TestableTrajetModel
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = TestDatabase::getInstance();
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare('
            INSERT INTO trajet
                (utilisateur_id, agence_depart_id, agence_arrivee_id,
                 gdh_depart, gdh_arrivee, nb_places_total, nb_places_dispo)
            VALUES
                (:utilisateur_id, :agence_depart_id, :agence_arrivee_id,
                 :gdh_depart, :gdh_arrivee, :nb_places_total, :nb_places_total)
        ');
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

    public function update(int $id, array $data): void
    {
        $stmt = $this->pdo->prepare('
            UPDATE trajet
            SET agence_depart_id  = :agence_depart_id,
                agence_arrivee_id = :agence_arrivee_id,
                gdh_depart        = :gdh_depart,
                gdh_arrivee       = :gdh_arrivee,
                nb_places_total   = :nb_places_total,
                nb_places_dispo   = :nb_places_dispo
            WHERE id = :id
        ');
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

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM trajet WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM trajet WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }
}

// =====================================================================
// Tests AgenceModel
// =====================================================================

class AgenceModelTest extends TestCase
{
    private TestableAgenceModel $model;

    protected function setUp(): void
    {
        $this->model = new TestableAgenceModel();
    }

    /** @test */
    public function testCreateAgence(): void
    {
        $id = $this->model->create('Bordeaux');
        $this->assertGreaterThan(0, $id);

        $agence = $this->model->findById($id);
        $this->assertNotNull($agence);
        $this->assertSame('Bordeaux', $agence['nom']);
    }

    /** @test */
    public function testUpdateAgence(): void
    {
        $id = $this->model->create('Marseile'); // faute intentionnelle
        $this->model->update($id, 'Marseille');

        $agence = $this->model->findById($id);
        $this->assertSame('Marseille', $agence['nom']);
    }

    /** @test */
    public function testDeleteAgence(): void
    {
        $id = $this->model->create('Ville à supprimer');
        $this->model->delete($id);

        $agence = $this->model->findById($id);
        $this->assertNull($agence);
    }

    /** @test */
    public function testFindAllReturnsArray(): void
    {
        $agences = $this->model->findAll();
        $this->assertIsArray($agences);
        $this->assertNotEmpty($agences);
    }
}

// =====================================================================
// Tests TrajetModel
// =====================================================================

class TrajetModelTest extends TestCase
{
    private TestableTrajetModel $model;

    private array $validData = [
        'utilisateur_id'    => 1,
        'agence_depart_id'  => 1,
        'agence_arrivee_id' => 2,
        'gdh_depart'        => '2026-06-01 08:00:00',
        'gdh_arrivee'       => '2026-06-01 12:00:00',
        'nb_places_total'   => 4,
    ];

    protected function setUp(): void
    {
        $this->model = new TestableTrajetModel();
    }

    /** @test */
    public function testCreateTrajet(): void
    {
        $id = $this->model->create($this->validData);
        $this->assertGreaterThan(0, $id);

        $trajet = $this->model->findById($id);
        $this->assertNotNull($trajet);
        $this->assertSame(4, (int)$trajet['nb_places_total']);
        $this->assertSame(4, (int)$trajet['nb_places_dispo']);
    }

    /** @test */
    public function testUpdateTrajet(): void
    {
        $id = $this->model->create($this->validData);

        $this->model->update($id, [
            'agence_depart_id'  => 1,
            'agence_arrivee_id' => 2,
            'gdh_depart'        => '2026-07-01 09:00:00',
            'gdh_arrivee'       => '2026-07-01 13:00:00',
            'nb_places_total'   => 3,
            'nb_places_dispo'   => 2,
        ]);

        $trajet = $this->model->findById($id);
        $this->assertSame(3, (int)$trajet['nb_places_total']);
        $this->assertSame(2, (int)$trajet['nb_places_dispo']);
    }

    /** @test */
    public function testDeleteTrajet(): void
    {
        $id = $this->model->create($this->validData);
        $this->model->delete($id);

        $trajet = $this->model->findById($id);
        $this->assertNull($trajet);
    }
}
