<?php
$livrables = $data['livrables'] ?? [];
$id_projet = $data['id_projet'] ?? 0;
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Définir un livrable</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="ajouter_livrable">
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de livrable</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="document">Document</option>
                                <option value="code">Code source</option>
                                <option value="presentation">Présentation</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date_limite" class="form-label">Date limite</label>
                            <input type="date" class="form-control" id="date_limite" name="date_limite" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="obligatoire" name="obligatoire">
                                <label class="form-check-label" for="obligatoire">
                                    Livrable obligatoire
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Ajouter le livrable</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Livrables attendus</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Date limite</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($livrables as $livrable): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($livrable['titre']); ?>
                                        <?php if ($livrable['description']): ?>
                                        <small class="d-block text-muted">
                                            <?php echo htmlspecialchars($livrable['description']); ?>
                                        </small>
                                        <?php endif; ?>
                                        <?php if ($livrable['obligatoire']): ?>
                                        <span class="badge bg-danger">Obligatoire</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo ucfirst($livrable['type']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($livrable['date_limite'])); ?></td>
                                    <td>
                                        <?php
                                        $now = new DateTime();
                                        $limit = new DateTime($livrable['date_limite']);
                                        if ($now > $limit) {
                                            echo '<span class="badge bg-danger">En retard</span>';
                                        } elseif ($now->diff($limit)->days <= 7) {
                                            echo '<span class="badge bg-warning">Bientôt</span>';
                                        } else {
                                            echo '<span class="badge bg-success">À venir</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editLivrable<?php echo $livrable['id_livrable']; ?>">
                                                Modifier
                                            </button>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="supprimer_livrable">
                                                <input type="hidden" name="id_livrable" value="<?php echo $livrable['id_livrable']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Supprimer ce livrable ?')">
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

<?php foreach ($livrables as $livrable): ?>
<div class="modal fade" id="editLivrable<?php echo $livrable['id_livrable']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier le livrable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="action" value="modifier_livrable">
                    <input type="hidden" name="id_livrable" value="<?php echo $livrable['id_livrable']; ?>">
                    
                    <div class="mb-3">
                        <label for="titre<?php echo $livrable['id_livrable']; ?>" class="form-label">Titre</label>
                        <input type="text" class="form-control" 
                               id="titre<?php echo $livrable['id_livrable']; ?>" 
                               name="titre" 
                               value="<?php echo htmlspecialchars($livrable['titre']); ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description<?php echo $livrable['id_livrable']; ?>" class="form-label">Description</label>
                        <textarea class="form-control" 
                                  id="description<?php echo $livrable['id_livrable']; ?>" 
                                  name="description" 
                                  rows="3"><?php echo htmlspecialchars($livrable['description']); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type<?php echo $livrable['id_livrable']; ?>" class="form-label">Type de livrable</label>
                        <select class="form-select" 
                                id="type<?php echo $livrable['id_livrable']; ?>" 
                                name="type" required>
                            <option value="document" <?php echo $livrable['type'] == 'document' ? 'selected' : ''; ?>>Document</option>
                            <option value="code" <?php echo $livrable['type'] == 'code' ? 'selected' : ''; ?>>Code source</option>
                            <option value="presentation" <?php echo $livrable['type'] == 'presentation' ? 'selected' : ''; ?>>Présentation</option>
                            <option value="autre" <?php echo $livrable['type'] == 'autre' ? 'selected' : ''; ?>>Autre</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="date_limite<?php echo $livrable['id_livrable']; ?>" class="form-label">Date limite</label>
                        <input type="date" class="form-control" 
                               id="date_limite<?php echo $livrable['id_livrable']; ?>" 
                               name="date_limite" 
                               value="<?php echo date('Y-m-d', strtotime($livrable['date_limite'])); ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="obligatoire<?php echo $livrable['id_livrable']; ?>" 
                                   name="obligatoire"
                                   <?php echo $livrable['obligatoire'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="obligatoire<?php echo $livrable['id_livrable']; ?>">
                                Livrable obligatoire
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>