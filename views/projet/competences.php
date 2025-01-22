<?php
$competences = $data['competences'] ?? [];
$id_projet = $data['id_projet'] ?? 0;
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Ajouter une compétence</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="ajouter_competence">
                        
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="libelle" class="form-label">Libellé</label>
                            <input type="text" class="form-control" id="libelle" name="libelle" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="niveau" class="form-label">Niveau requis</label>
                            <select class="form-select" id="niveau" name="niveau" required>
                                <option value="1">Niveau 1 - Novice</option>
                                <option value="2">Niveau 2 - Intermédiaire</option>
                                <option value="3">Niveau 3 - Avancé</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Ajouter la compétence</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Compétences du projet</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                    <th>Niveau</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($competences as $competence): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($competence['code']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($competence['libelle']); ?>
                                        <?php if ($competence['description']): ?>
                                        <small class="d-block text-muted">
                                            <?php echo htmlspecialchars($competence['description']); ?>
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>Niveau <?php echo $competence['niveau']; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editCompetence<?php echo $competence['id_competence']; ?>">
                                                Modifier
                                            </button>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="supprimer_competence">
                                                <input type="hidden" name="id_competence" value="<?php echo $competence['id_competence']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Supprimer cette compétence ?')">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php foreach ($competences as $competence): ?>
<div class="modal fade" id="editCompetence<?php echo $competence['id_competence']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la compétence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="action" value="modifier_competence">
                    <input type="hidden" name="id_competence" value="<?php echo $competence['id_competence']; ?>">
                    
                    <div class="mb-3">
                        <label for="code<?php echo $competence['id_competence']; ?>" class="form-label">Code</label>
                        <input type="text" class="form-control" 
                               id="code<?php echo $competence['id_competence']; ?>" 
                               name="code" 
                               value="<?php echo htmlspecialchars($competence['code']); ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="libelle<?php echo $competence['id_competence']; ?>" class="form-label">Libellé</label>
                        <input type="text" class="form-control" 
                               id="libelle<?php echo $competence['id_competence']; ?>" 
                               name="libelle" 
                               value="<?php echo htmlspecialchars($competence['libelle']); ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description<?php echo $competence['id_competence']; ?>" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description<?php echo $competence['id_competence']; ?>" 
                                  name="description" 
                                  rows="3"><?php echo htmlspecialchars($competence['description']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="niveau<?php echo $competence['id_competence']; ?>" class="form-label">Niveau requis</label>
                        <select class="form-select" 
                                id="niveau<?php echo $competence['id_competence']; ?>" 
                                name="niveau" required>
                            <option value="1" <?php echo $competence['niveau'] == 1 ? 'selected' : ''; ?>>Niveau 1 - Novice</option>
                            <option value="2" <?php echo $competence['niveau'] == 2 ? 'selected' : ''; ?>>Niveau 2 - Intermédiaire</option>
                            <option value="3" <?php echo $competence['niveau'] == 3 ? 'selected' : ''; ?>>Niveau 3 - Avancé</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>