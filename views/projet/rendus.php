<?php
$rendus = $data['rendus'] ?? [];
$groupes = $data['groupes'] ?? [];
$enseignants = $data['enseignants'] ?? [];
$id_projet = $data['id_projet'] ?? 0;
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Créer un rendu</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="creer_rendu">
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre du rendu</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="date_limite" class="form-label">Date limite</label>
                            <input type="datetime-local" class="form-control" id="date_limite" name="date_limite" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type_evaluation" class="form-label">Type d'évaluation</label>
                            <select class="form-select" id="type_evaluation" name="type_evaluation" required>
                                <option value="groupe">Groupe</option>
                                <option value="individuel">Individuel</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Créer le rendu</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Rendus existants</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date limite</th>
                                    <th>Type</th>
                                    <th>Évaluations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rendus as $rendu): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($rendu['titre']); ?>
                                        <?php if ($rendu['description']): ?>
                                        <small class="d-block text-muted">
                                            <?php echo htmlspecialchars($rendu['description']); ?>
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($rendu['date_limite'])); ?></td>
                                    <td><?php echo $rendu['type_evaluation']; ?></td>
                                    <td><?php echo $rendu['nb_evaluations']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#evaluer<?php echo $rendu['id_rendu']; ?>">
                                            Évaluer
                                        </button>
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

<?php foreach ($rendus as $rendu): ?>
<div class="modal fade" id="evaluer<?php echo $rendu['id_rendu']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Évaluer le rendu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <input type="hidden" name="action" value="creer_evaluation">
                    <input type="hidden" name="id_rendu" value="<?php echo $rendu['id_rendu']; ?>">
                    
                    <div class="mb-3">
                        <label for="groupe<?php echo $rendu['id_rendu']; ?>" class="form-label">Groupe</label>
                        <select class="form-select" id="groupe<?php echo $rendu['id_rendu']; ?>" name="id_groupe" required>
                            <?php foreach ($groupes as $groupe): ?>
                            <option value="<?php echo $groupe['id_groupe']; ?>">
                                <?php echo htmlspecialchars($groupe['titre']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="note<?php echo $rendu['id_rendu']; ?>" class="form-label">Note</label>
                        <input type="number" class="form-control" 
                               id="note<?php echo $rendu['id_rendu']; ?>" 
                               name="note" 
                               min="0" max="20" step="0.5" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="commentaire<?php echo $rendu['id_rendu']; ?>" class="form-label">Commentaire</label>
                        <textarea class="form-control" 
                                  id="commentaire<?php echo $rendu['id_rendu']; ?>" 
                                  name="commentaire" 
                                  rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enregistrer l'évaluation</button>
                </form>
                
                <hr>
                
                <form method="POST" class="mt-3">
                    <input type="hidden" name="action" value="deleguer_evaluation">
                    <input type="hidden" name="id_evaluation" value="<?php echo $rendu['id_rendu']; ?>">
                    
                    <div class="mb-3">
                        <label for="deleguer<?php echo $rendu['id_rendu']; ?>" class="form-label">
                            Déléguer l'évaluation à
                        </label>
                        <select class="form-select" id="deleguer<?php echo $rendu['id_rendu']; ?>" 
                                name="id_nouvel_evaluateur" required>
                            <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?php echo $enseignant['id']; ?>">
                                <?php echo htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">Déléguer l'évaluation</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>