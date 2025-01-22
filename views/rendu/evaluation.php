<?php
$rendu = $data['rendu'] ?? null;
if (!$rendu) {
    header('Location: index.php?module=rendu&action=list');
    exit;
}
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Évaluation du rendu</h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h4>Informations du rendu</h4>
                    <dl class="row">
                        <dt class="col-sm-4">Projet</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($rendu['projet_titre']); ?></dd>
                        
                        <dt class="col-sm-4">Groupe</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($rendu['groupe_titre']); ?></dd>
                        
                        <dt class="col-sm-4">Étudiant</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($rendu['etudiant_login']); ?></dd>
                        
                        <dt class="col-sm-4">Date de rendu</dt>
                        <dd class="col-sm-8"><?php echo date('d/m/Y H:i', strtotime($rendu['date_creation'])); ?></dd>
                        
                        <dt class="col-sm-4">Fichier</dt>
                        <dd class="col-sm-8">
                            <a href="index.php?module=rendu&action=download&id=<?php echo $rendu['id_rendu']; ?>" 
                               class="btn btn-sm btn-primary">
                                Télécharger le rendu
                            </a>
                        </dd>
                    </dl>
                </div>
                
                <div class="col-md-6">
                    <h4>Évaluation</h4>
                    <form method="POST">
                        <input type="hidden" name="id_rendu" value="<?php echo $rendu['id_rendu']; ?>">
                        
                        <div class="mb-3">
                            <label for="note" class="form-label">Note sur 20</label>
                            <input type="number" class="form-control" id="note" name="note" 
                                   min="0" max="20" step="0.5" 
                                   value="<?php echo $rendu['note'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire</label>
                            <textarea class="form-control" id="commentaire" name="commentaire" 
                                      rows="4"><?php echo $rendu['commentaire'] ?? ''; ?></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Enregistrer l'évaluation</button>
                            <a href="index.php?module=rendu&action=list" class="btn btn-secondary">Retour à la liste</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>