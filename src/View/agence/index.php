<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Agences</h1>
    <div class="d-flex gap-2">
        <a href="/admin/agences/creer" class="btn btn-success btn-sm">+ Nouvelle agence</a>
        <a href="/admin" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
    </div>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($agences as $agence): ?>
        <tr>
            <td><?= (int)$agence['id'] ?></td>
            <td><?= htmlspecialchars($agence['nom']) ?></td>
            <td class="d-flex gap-1">
                <a href="/admin/agences/<?= (int)$agence['id'] ?>/modifier"
                   class="btn btn-outline-primary btn-sm">Modifier</a>
                <form method="post" action="/admin/agences/<?= (int)$agence['id'] ?>/supprimer"
                      onsubmit="return confirm('Supprimer cette agence ?')">
                    <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
