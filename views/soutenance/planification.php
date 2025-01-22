<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Planification des passages - <?php echo htmlspecialchars($soutenance['projet_titre']); ?></h2>
        </div>
        <div class="card-body">
            <?php if (empty($groupes)): ?>
                <div class="alert alert-info">
                    Tous les groupes ont déjà été planifiés pour cette soutenance.
                </div>
            <?php else: ?>
                <form method="POST" action="index.php?module=soutenance&action=planifier">
                    <input type="hidden" name="id_soutenance" value="<?php echo $soutenance['id_soutenance']; ?>">
                    
                    <div class="mb-3">
                        <label for="id_groupe" class="form-label">Groupe</label>
                        <select class="form-select" id="id_groupe" name="id_groupe" required>
                            <option value="">Sélectionner un groupe</option>
                            <?php foreach ($groupes as $groupe): ?>
                                <option value="<?php echo $groupe['id_groupe']; ?>">
                                    <?php echo htmlspecialchars($groupe['titre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="heure_passage" class="form-label">Heure de passage</label>
                        <input type="time" class="form-control" id="heure_passage" name="heure_passage" required>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Planifier le passage</button>
                        <a href="index.php?module=soutenance&action=list" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>