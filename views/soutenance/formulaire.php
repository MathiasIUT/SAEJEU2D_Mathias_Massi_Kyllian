<?php
$projets = $data['projets'] ?? [];
$error = $data['error'] ?? '';
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Créer une soutenance</h2>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="id_projet" class="form-label">Projet</label>
                    <select class="form-control" id="id_projet" name="id_projet" required>
                        <option value="">Sélectionner un projet</option>
                        <?php foreach ($projets as $projet): ?>
                            <option value="<?php echo $projet['id_projet']; ?>">
                                <?php echo htmlspecialchars($projet['titre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                
                <div class="mb-3">
                    <label for="duree" class="form-label">Durée (minutes)</label>
                    <input type="number" class="form-control" id="duree" name="duree" 
                           min="5" step="5" value="20" required>
                </div>
                
                <div class="mb-3">
                    <label for="salle" class="form-label">Salle</label>
                    <input type="text" class="form-control" id="salle" name="salle" required>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Créer la soutenance</button>
                    <a href="index.php?module=soutenance&action=list" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </form>
        </div>
    </div>
</div>