<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Utilisateurs</h1>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">← Tableau de bord</a>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Rôle</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= (int)$u['id'] ?></td>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['telephone']) ?></td>
            <td>
                <?php if ($u['est_admin']): ?>
                    <span class="badge" style="background-color:#cd2c2e;">Admin</span>
                <?php else: ?>
                    <span class="badge bg-secondary">Employé</span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
