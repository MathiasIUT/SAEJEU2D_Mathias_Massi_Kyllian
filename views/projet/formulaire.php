<?php
$projet = $data['projet'] ?? null;
$error = $data['error'] ?? '';
$enseignants = $data['enseignants'] ?? [];
$co_responsables = explode(',', $projet['co_responsables'] ?? '');
$intervenants = explode(',', $projet['intervenants'] ?? '');
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2><?php echo $projet ? 'Modifier le projet' : 'Créer un projet'; ?></h2>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" 
                           value="<?php echo $projet['titre'] ?? ''; ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" 
                            rows="3" required><?php echo $projet['description'] ?? ''; ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre</label>
                    <select class="form-control" id="semestre" name="semestre" required>
                        <option value="S1" <?php echo ($projet['semestre'] ?? '') === 'S1' ? 'selected' : ''; ?>>S1</option>
                        <option value="S2" <?php echo ($projet['semestre'] ?? '') === 'S2' ? 'selected' : ''; ?>>S2</option>
                        <option value="S3" <?php echo ($projet['semestre'] ?? '') === 'S3' ? 'selected' : ''; ?>>S3</option>
                        <option value="S4" <?php echo ($projet['semestre'] ?? '') === 'S4' ? 'selected' : ''; ?>>S4</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="trello_link" class="form-label">Lien Trello</label>
                    <input type="url" class="form-control" id="trello_link" name="trello_link" 
                           value="<?php echo $projet['trello_link'] ?? ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label for="git_link" class="form-label">Lien Git</label>
                    <input type="url" class="form-control" id="git_link" name="git_link" 
                           value="<?php echo $projet['git_link'] ?? ''; ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Co-responsables</label>
                    <div class="row">
                        <?php foreach ($enseignants as $enseignant): ?>
                        <?php if ($enseignant['id'] != $_SESSION['user']['id']): ?>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="co_responsables[]" 
                                       value="<?php echo $enseignant['id']; ?>"
                                       <?php echo in_array($enseignant['id'], $co_responsables) ? 'checked' : ''; ?>>
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']); ?>
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Intervenants</label>
                    <div class="row">
                        <?php foreach ($enseignants as $enseignant): ?>
                        <?php if ($enseignant['id'] != $_SESSION['user']['id']): ?>
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="intervenants[]" 
                                       value="<?php echo $enseignant['id']; ?>"
                                       <?php echo in_array($enseignant['id'], $intervenants) ? 'checked' : ''; ?>>
                                <label class="form-check-label">
                                    <?php echo htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']); ?>
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?php echo $projet ? 'Modifier' : 'Créer'; ?>
                </button>
            </form>
        </div>
    </div>
</div>