<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Soumettre un rendu</h2>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="id_projet" class="form-label">Projet</label>
                    <select class="form-select" id="id_projet" name="id_projet" required>
                        <option value="">Sélectionner un projet</option>
                        <?php foreach ($projets as $projet): ?>
                            <option value="<?php echo $projet['id_projet']; ?>">
                                <?php echo htmlspecialchars($projet['titre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="fichiers" class="form-label">Fichiers</label>
                    <input type="file" class="form-control" id="fichiers" name="fichiers[]" multiple required>
                    <div class="form-text">
                        Formats acceptés : PDF, ZIP, DOC, DOCX, TXT, ODT<br>
                        Taille maximale : 50 MB par fichier
                    </div>
                </div>

                <div class="mb-3">
                    <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                    <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Soumettre le rendu</button>
                    <a href="index.php?module=rendu&action=list" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </form>
        </div>
    </div>
</div>