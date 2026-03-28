-- ============================================================
-- TOUCHE PAS AU KLAXON — Script de création de la base de données
-- ============================================================

CREATE DATABASE IF NOT EXISTS klaxon CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE klaxon;

-- ============================================================
-- TABLE : agence
-- ============================================================
CREATE TABLE agence (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : utilisateur
-- ============================================================
CREATE TABLE utilisateur (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    est_admin TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- TABLE : trajet
-- ============================================================
CREATE TABLE trajet (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNSIGNED NOT NULL,
    agence_depart_id INT UNSIGNED NOT NULL,
    agence_arrivee_id INT UNSIGNED NOT NULL,
    gdh_depart DATETIME NOT NULL,
    gdh_arrivee DATETIME NOT NULL,
    nb_places_total TINYINT UNSIGNED NOT NULL DEFAULT 1,
    nb_places_dispo TINYINT UNSIGNED NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_trajet_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(id),
    CONSTRAINT fk_trajet_depart     FOREIGN KEY (agence_depart_id)  REFERENCES agence(id),
    CONSTRAINT fk_trajet_arrivee    FOREIGN KEY (agence_arrivee_id) REFERENCES agence(id),
    CONSTRAINT chk_places CHECK (nb_places_dispo <= nb_places_total),
    CONSTRAINT chk_dates  CHECK (gdh_arrivee > gdh_depart),
    CONSTRAINT chk_agences CHECK (agence_depart_id <> agence_arrivee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DONNÉES : agences (issues du fichier agences.txt)
-- ============================================================
INSERT INTO agence (nom) VALUES
    ('Paris'), ('Lyon'), ('Marseille'), ('Toulouse'), ('Nice'),
    ('Nantes'), ('Strasbourg'), ('Montpellier'), ('Bordeaux'),
    ('Lille'), ('Rennes'), ('Reims');

-- ============================================================
-- DONNÉES : utilisateurs (issues du fichier users.txt)
-- Mot de passe par défaut : Password1! (hashé en bcrypt)
-- Hash généré : password_hash('Password1!', PASSWORD_BCRYPT)
-- ============================================================
INSERT INTO utilisateur (nom, prenom, telephone, email, mot_de_passe, est_admin) VALUES
    ('Martin',    'Alexandre', '0612345678', 'alexandre.martin@email.fr',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Dubois',    'Sophie',    '0698765432', 'sophie.dubois@email.fr',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Bernard',   'Julien',    '0622446688', 'julien.bernard@email.fr',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Moreau',    'Camille',   '0611223344', 'camille.moreau@email.fr',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Lefèvre',   'Lucie',     '0777889900', 'lucie.lefevre@email.fr',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Leroy',     'Thomas',    '0655443322', 'thomas.leroy@email.fr',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Roux',      'Chloé',     '0633221199', 'chloe.roux@email.fr',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Petit',     'Maxime',    '0766778899', 'maxime.petit@email.fr',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Garnier',   'Laura',     '0688776655', 'laura.garnier@email.fr',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Dupuis',    'Antoine',   '0744556677', 'antoine.dupuis@email.fr',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Lefebvre',  'Emma',      '0699887766', 'emma.lefebvre@email.fr',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Fontaine',  'Louis',     '0655667788', 'louis.fontaine@email.fr',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Chevalier', 'Clara',     '0788990011', 'clara.chevalier@email.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Robin',     'Nicolas',   '0644332211', 'nicolas.robin@email.fr',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Gauthier',  'Marine',    '0677889922', 'marine.gauthier@email.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Fournier',  'Pierre',    '0722334455', 'pierre.fournier@email.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Girard',    'Sarah',     '0688665544', 'sarah.girard@email.fr',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Lambert',   'Hugo',      '0611223366', 'hugo.lambert@email.fr',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Masson',    'Julie',     '0733445566', 'julie.masson@email.fr',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    ('Henry',     'Arthur',    '0666554433', 'arthur.henry@email.fr',      '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0),
    -- Compte administrateur
    ('Admin',     'Admin',     '0600000000', 'admin@klaxon.fr',            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- ============================================================
-- DONNÉES : trajets (jeu d'essais)
-- IDs agences : Paris=1, Lyon=2, Marseille=3, Toulouse=4, Nice=5
--               Nantes=6, Strasbourg=7, Montpellier=8, Bordeaux=9, Lille=10
-- ============================================================
INSERT INTO trajet (utilisateur_id, agence_depart_id, agence_arrivee_id, gdh_depart, gdh_arrivee, nb_places_total, nb_places_dispo) VALUES
    (1,  1, 2,  '2026-04-10 08:00:00', '2026-04-10 12:00:00', 4, 3),
    (2,  2, 3,  '2026-04-11 09:00:00', '2026-04-11 14:00:00', 3, 2),
    (3,  3, 1,  '2026-04-12 07:30:00', '2026-04-12 13:00:00', 5, 4),
    (4,  1, 4,  '2026-04-13 06:00:00', '2026-04-13 11:30:00', 4, 1),
    (5,  5, 1,  '2026-04-14 08:00:00', '2026-04-14 14:00:00', 2, 2),
    (6,  6, 1,  '2026-04-15 07:00:00', '2026-04-15 11:00:00', 3, 0),
    (7,  1, 7,  '2026-04-16 10:00:00', '2026-04-16 16:30:00', 4, 3),
    (8,  8, 1,  '2026-04-17 09:30:00', '2026-04-17 13:30:00', 3, 2),
    (9,  1, 9,  '2026-04-18 08:00:00', '2026-04-18 13:00:00', 5, 5),
    (10, 10, 2, '2026-04-19 07:00:00', '2026-04-19 10:00:00', 4, 2);
