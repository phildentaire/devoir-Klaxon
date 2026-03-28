<h1 class="mb-4">Trajets disponibles</h1>

<?php if (empty($trajets)): ?>
    <div class="alert alert-info">Aucun trajet disponible pour le moment.</div>
<?php else: ?>
<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>Départ</th>
            <th>Date départ</th>
            <th>Arrivée</th>
            <th>Date arrivée</th>
            <th>Places dispo.</th>
            <?php if (!empty($_SESSION['user'])): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($trajets as $trajet): ?>
        <tr>
            <td><strong><?= htmlspecialchars($trajet['agence_depart']) ?></strong></td>
            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($trajet['gdh_depart']))) ?></td>
            <td><strong><?= htmlspecialchars($trajet['agence_arrivee']) ?></strong></td>
            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($trajet['gdh_arrivee']))) ?></td>
            <td>
                <span class="badge bg-success"><?= (int)$trajet['nb_places_dispo'] ?> place(s)</span>
            </td>
            <?php if (!empty($_SESSION['user'])): ?>
            <td class="d-flex gap-1 flex-wrap">
                <!-- Bouton Détails (modale) -->
                <button type="button" class="btn btn-outline-secondary btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-<?= (int)$trajet['id'] ?>">
                    Détails
                </button>

                <?php if ($_SESSION['user']['id'] === $trajet['utilisateur_id']): ?>
                    <a href="/trajets/<?= (int)$trajet['id'] ?>/modifier"
                       class="btn btn-outline-primary btn-sm">Modifier</a>
                    <form method="post" action="/trajets/<?= (int)$trajet['id'] ?>/supprimer"
                          onsubmit="return confirm('Supprimer ce trajet ?')">
                        <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                    </form>
                <?php endif; ?>
            </td>
            <?php endif; ?>
        </tr>

        <!-- Modale détails -->
        <?php if (!empty($_SESSION['user'])): ?>
        <div class="modal fade" id="modal-<?= (int)$trajet['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <?= htmlspecialchars($trajet['agence_depart']) ?>
                            → <?= htmlspecialchars($trajet['agence_arrivee']) ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Proposé par :</strong>
                            <?= htmlspecialchars($trajet['user_prenom'] . ' ' . $trajet['user_nom']) ?>
                        </p>
                        <p><strong>Téléphone :</strong>
                            <?= htmlspecialchars($trajet['user_telephone']) ?>
                        </p>
                        <p><strong>Email :</strong>
                            <?= htmlspecialchars($trajet['user_email']) ?>
                        </p>
                        <p><strong>Places totales :</strong> <?= (int)$trajet['nb_places_total'] ?></p>
                        <p><strong>Places disponibles :</strong> <?= (int)$trajet['nb_places_dispo'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
