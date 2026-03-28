<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Touche Pas au Klaxon</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

<!-- HEADER -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">

        <?php if (!empty($_SESSION['user']['est_admin'])): ?>
            <a class="navbar-brand fw-bold" href="/admin">🚗 Klaxon</a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="/admin/utilisateurs" class="btn btn-outline-light btn-sm">Utilisateurs</a>
                <a href="/admin/agences"      class="btn btn-outline-light btn-sm">Agences</a>
                <a href="/admin/trajets"       class="btn btn-outline-light btn-sm">Trajets</a>
                <a href="/deconnexion"         class="btn btn-danger btn-sm">Déconnexion</a>
            </div>

        <?php elseif (!empty($_SESSION['user'])): ?>
            <a class="navbar-brand fw-bold" href="/">🚗 Klaxon</a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="/trajets/creer" class="btn btn-success btn-sm">+ Proposer un trajet</a>
                <span class="text-white">
                    <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
                </span>
                <a href="/deconnexion" class="btn btn-outline-light btn-sm">Déconnexion</a>
            </div>

        <?php else: ?>
            <a class="navbar-brand fw-bold" href="/">🚗 Touche Pas au Klaxon</a>
            <div class="ms-auto">
                <a href="/connexion" class="btn btn-outline-light btn-sm">Connexion</a>
            </div>
        <?php endif; ?>

    </div>
</nav>

<!-- CONTENU -->
<main class="container my-4">
    <?= $content ?>
</main>

<!-- FOOTER -->
<footer class="footer bg-dark text-white text-center py-3 mt-5">
    <p class="mb-0">🚗 Touche Pas au Klaxon &mdash; &copy; <?= date('Y') ?></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
