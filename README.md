# 🚗 Touche Pas au Klaxon

Application de covoiturage inter-sites développée en PHP avec architecture MVC.

---

## Fonctionnalités :

- **Page d'accueil** : liste des trajets disponibles (places restantes, départ futur), triée par date croissante.
- **Utilisateur connecté** : voir les détails d'un trajet (modale), proposer / modifier / supprimer ses propres trajets.
- **Administrateur** : gestion complète des trajets, utilisateurs et agences (CRUD).

---

## Stack technique :

| Élément       | Choix                         |
|---------------|-------------------------------|
| Langage       | PHP 8.1+                      |
| Architecture  | MVC maison (sans framework)   |
| Base de données | MySQL / MariaDB             |
| Front-end     | Bootstrap 5.3 + CSS personnalisé |
| Tests         | PHPUnit 10                    |
| Qualité code  | PHPStan                       |
| Autoload      | Composer PSR-4                |

---

## Prérequis :

- PHP ≥ 8.1 avec extensions `pdo`, `pdo_mysql`
- MySQL ou MariaDB
- Composer
- Apache avec `mod_rewrite` activé (ou nginx équivalent)

---

## Installation :

### 1. Cloner le dépôt :

```bash
git clone https://github.com/votre-user/klaxon.git
cd klaxon
```

### 2. Installer les dépendances :

```bash
composer install
```

### 3. Créer et alimenter la base de données :

```bash
mysql -u root -p < klaxon_db.sql
```

### 4. Configurer la base de données :

Éditer `config/database.php` :

```php
return [
    'host'   => 'localhost',
    'dbname' => 'klaxon',
    'user'   => 'root',
    'pass'   => 'votre_mot_de_passe',
    'charset'=> 'utf8mb4',
];
```

### 5. Configurer Apache :

Le document root doit pointer sur le dossier `public/` :

```apache
<VirtualHost *:80>
    ServerName klaxon.local
    DocumentRoot /chemin/vers/klaxon/public

    <Directory /chemin/vers/klaxon/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Activer `mod_rewrite` :

```bash
a2enmod rewrite
systemctl restart apache2
```

### 6. Lancer l'application :

Accéder à `http://klaxon.local` dans votre navigateur.

---

## Comptes de test :

| Rôle          | Email                      | Mot de passe |
|---------------|---------------------------|--------------|
| Administrateur | `admin@klaxon.fr`         | `password` |
| Utilisateur    | `alexandre.martin@email.fr` | `password` |

> Le mot de passe par défaut de tous les employés importés est `password`.

---

## Lancer les tests PHPUnit :

```bash
vendor/bin/phpunit
```

Les tests couvrent les opérations d'écriture en base (create, update, delete) des modèles `AgenceModel` et `TrajetModel` via une base SQLite en mémoire (aucune connexion MySQL requise).

---

## Lancer PHPStan (analyse statique) :

```bash
vendor/bin/phpstan analyse src --level=5
```

---

## Structure du projet :

```
klaxon/
├── config/
│   └── database.php          # Configuration BDD
├── public/
│   ├── index.php             # Point d'entrée unique
│   ├── .htaccess             # Réécriture d'URL
│   └── css/
│       └── app.css           # Styles (palette imposée)
├── src/
│   ├── Core/
│   │   ├── Controller.php    # Contrôleur de base
│   │   ├── Database.php      # Singleton PDO
│   │   ├── Model.php         # Modèle de base
│   │   └── Router.php        # Routeur
│   ├── Controller/
│   │   ├── AuthController.php
│   │   ├── TrajetController.php
│   │   ├── AgenceController.php
│   │   └── AdminController.php
│   ├── Model/
│   │   ├── TrajetModel.php
│   │   ├── AgenceModel.php
│   │   └── UtilisateurModel.php
│   └── View/
│       ├── layout/main.php   # Template principal
│       ├── auth/login.php
│       ├── trajet/
│       ├── agence/
│       └── admin/
├── tests/
│   └── ModelTest.php         # Tests PHPUnit
├── klaxon_db.sql             # Script BDD (création + données)
├── composer.json
├── phpstan-boostrap.php
├── phpstan.neon
├── phpunit.xml
└── README.md
```

---

## Palette de couleurs :

| Couleur      | Hex       | Usage               |
|-------------|-----------|---------------------|
| Bleu clair   | `#f1f8fc` | Fond de page        |
| Bleu primaire| `#0074c7` | Navbar, boutons     |
| Bleu foncé   | `#00497c` | En-têtes tableaux   |
| Ardoise      | `#384050` | Footer, texte       |
| Rouge        | `#cd2c2e` | Danger, admin badge |
| Vert         | `#82b864` | Succès, places dispo|

---

## MLD (Modèle Logique de Données) :

```
UTILISATEUR (id, nom, prenom, telephone, email, mot_de_passe, est_admin)
AGENCE (id, nom)
TRAJET (id, #utilisateur_id, #agence_depart_id, #agence_arrivee_id, gdh_depart, gdh_arrivee, nb_places_total, nb_places_dispo, created_at)
```

---

## Auteur :

Développé par Phild Revel dans le cadre d'un exercice pédagogique issu du Centre Européen de Formation.
