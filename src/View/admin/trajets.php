<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Tous les trajets</h1>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
</div>

<?php if (empty($trajets)): ?>
    <div class="alert alert-info">Aucun trajet enregistré.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Départ</th>
            <th>Date départ</th>
            <th>Arrivée</th>
            <th>Date arrivée</th>
            <th>Places</th>
            <th>Proposé par</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($trajets as $t): ?>
        <tr>
            <td><?= (int)$t['id'] ?></td>
            <td><?= htmlspecialchars($t['agence_depart']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($t['gdh_depart'])) ?></td>
            <td><?= htmlspecialchars($t['agence_arrivee']) ?></td>
            <td><?= date('d/m/Y H:i', strtotime($t['gdh_arrivee'])) ?></td>
            <td><?= (int)$t['nb_places_dispo'] ?> / <?= (int)$t['nb_places_total'] ?></td>
            <td><?= htmlspecialchars($t['user_prenom'] . ' ' . $t['user_nom']) ?></td>
            <td>
                <form method="post" action="/admin/trajets/<?= (int)$t['id'] ?>/supprimer"
                      onsubmit="return confirm('Supprimer ce trajet ?')">
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
