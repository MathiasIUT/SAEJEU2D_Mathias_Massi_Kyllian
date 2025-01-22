<?php
$ressources = $data['ressources'] ?? [];
$id_projet = $data['id_projet'] ?? 0;
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Ajouter une ressource</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="ajouter_ressource">
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de ressource</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="video">Vidéo</option>
                                <option value="pdf">PDF</option>
                                <option value="code">Code source</option>
                                <option value="lien">Lien</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" id="url" name="url" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Ajouter la ressource</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Ressources disponibles</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Créateur</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ressources as $ressource): ?>
                                <tr class="<?php echo $ressource['est_mise_en_avant'] ? 'table-warning' : ''; ?>">
                                    <td>
                                        <a href="<?php echo htmlspecialchars($ressource['url']); ?>" 
                                           target="_blank" 
                                           class="text-decoration-none">
                                            <?php echo htmlspecialchars($ressource['titre']); ?>
                                        </a>
                                        <?php if ($ressource['description']): ?>
                                        <small class="d-block text-muted">
                                            <?php echo htmlspecialchars($ressource['description']); ?>
                                        </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo ucfirst($ressource['type']); ?></td>
                                    <td><?php echo htmlspecialchars($ressource['createur_login']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($ressource['date_creation'])); ?></td>
                                    <td>
                                        <?php if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['id'] === $ressource['id_createur']): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="action" value="mettre_en_avant">
                                            <input type="hidden" name="id_ressource" value="<?php echo $ressource['id_ressource']; ?>">
                                            <input type="hidden" name="mise_en_avant" value="<?php echo $ressource['est_mise_en_avant'] ? '0' : '1'; ?>">
                                            <button type="submit" class="btn btn-sm <?php echo $ressource['est_mise_en_avant'] ? 'btn-warning' : 'btn-outline-warning'; ?>">
                                                <?php echo $ressource['est_mise_en_avant'] ? 'Retirer mise en avant' : 'Mettre en avant'; ?>
                                            </button>
                                        </form>
                                        <?php endif; ?>
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