<?php $isEdit = $agence !== null; ?>
<h1 class="mb-4"><?= $isEdit ? 'Modifier l\'agence' : 'Nouvelle agence' ?></h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow-sm" style="max-width:400px;">
    <div class="card-body">
        <form method="post" action="<?= $isEdit ? '/admin/agences/' . (int)$agence['id'] . '/modifier' : '/admin/agences/creer' ?>">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la ville / agence</label>
                <input type="text" id="nom" name="nom" class="form-control" required
                       value="<?= $isEdit ? htmlspecialchars($agence['nom']) : '' ?>">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Enregistrer' : 'Créer' ?>
                </button>
                <a href="/admin/agences" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
