<?php $isEdit = $trajet !== null; ?>
<h1 class="mb-4"><?= $isEdit ? 'Modifier le trajet' : 'Proposer un trajet' ?></h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <!-- Infos utilisateur pré-remplies (non modifiables) -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Nom complet</label>
                <input type="text" class="form-control" readonly
                    value="<?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Téléphone</label>
                <input type="text" class="form-control" readonly
                    value="<?= htmlspecialchars($_SESSION['user']['telephone']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Email</label>
                <input type="text" class="form-control" readonly
                    value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">
            </div>
        </div>

        <hr>

        <form method="post" action="<?= $isEdit ? '/trajets/' . (int)$trajet['id'] . '/modifier' : '/trajets/creer' ?>">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="agence_depart_id" class="form-label">Agence de départ</label>
                    <select name="agence_depart_id" id="agence_depart_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= (int)$agence['id'] ?>"
                                <?= ($isEdit && $trajet['agence_depart_id'] == $agence['id']) || (isset($_POST['agence_depart_id']) && $_POST['agence_depart_id'] == $agence['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="agence_arrivee_id" class="form-label">Agence d'arrivée</label>
                    <select name="agence_arrivee_id" id="agence_arrivee_id" class="form-select" required>
                        <option value="">-- Choisir --</option>
                        <?php foreach ($agences as $agence): ?>
                            <option value="<?= (int)$agence['id'] ?>"
                                <?= ($isEdit && $trajet['agence_arrivee_id'] == $agence['id']) || (isset($_POST['agence_arrivee_id']) && $_POST['agence_arrivee_id'] == $agence['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($agence['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="gdh_depart" class="form-label">Date et heure de départ</label>
                    <input type="datetime-local" id="gdh_depart" name="gdh_depart" class="form-control" required
                        value="<?= $isEdit ? substr($trajet['gdh_depart'], 0, 16) : (isset($_POST['gdh_depart']) ? htmlspecialchars($_POST['gdh_depart']) : '') ?>">
                </div>
                <div class="col-md-6">
                    <label for="gdh_arrivee" class="form-label">Date et heure d'arrivée</label>
                    <input type="datetime-local" id="gdh_arrivee" name="gdh_arrivee" class="form-control" required
                        value="<?= $isEdit ? substr($trajet['gdh_arrivee'], 0, 16) : (isset($_POST['gdh_arrivee']) ? htmlspecialchars($_POST['gdh_arrivee']) : '') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nb_places_total" class="form-label">Nombre de places total</label>
                    <input type="number" id="nb_places_total" name="nb_places_total" class="form-control"
                           min="1" max="9" required
                           value="<?= $isEdit ? (int)$trajet['nb_places_total'] : (isset($_POST['nb_places_total']) ? (int)$_POST['nb_places_total'] : 1) ?>">
                </div>
                <?php if ($isEdit): ?>
                <div class="col-md-4">
                    <label for="nb_places_dispo" class="form-label">Places disponibles</label>
                    <input type="number" id="nb_places_dispo" name="nb_places_dispo" class="form-control"
                           min="0" max="<?= (int)$trajet['nb_places_total'] ?>" required
                           value="<?= (int)$trajet['nb_places_dispo'] ?>">
                </div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <?= $isEdit ? 'Enregistrer les modifications' : 'Proposer ce trajet' ?>
                </button>
                <a href="/" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
