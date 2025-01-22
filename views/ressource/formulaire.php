<?php
$error = $data['error'] ?? '';
$ressource = $data['ressource'] ?? null;
$isEdit = isset($ressource);
?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2><?php echo $isEdit ? 'Modifier la ressource' : 'Ajouter une ressource'; ?></h2>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre *</label>
                    <input type="text" class="form-control" id="titre" name="titre" 
                           value="<?php echo htmlspecialchars($ressource['titre'] ?? ''); ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php 
                        echo htmlspecialchars($ressource['description'] ?? ''); 
                    ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="type" class="form-label">Type *</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="">Sélectionner un type</option>
                        <?php 
                        $types = ['document', 'video', 'lien', 'autre'];
                        foreach ($types as $type): 
                            $selected = ($ressource['type'] ?? '') === $type ? 'selected' : '';
                        ?>
                            <option value="<?php echo $type; ?>" <?php echo $selected; ?>>
                                <?php echo ucfirst($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="promotion" class="form-label">Promotion *</label>
                    <select class="form-control" id="promotion" name="promotion" required>
                        <option value="">Sélectionner une promotion</option>
                        <?php 
                        $promotions = ['BUT1', 'BUT2', 'BUT3'];
                        foreach ($promotions as $promo): 
                            $selected = ($ressource['promotion'] ?? '') === $promo ? 'selected' : '';
                        ?>
                            <option value="<?php echo $promo; ?>" <?php echo $selected; ?>>
                                <?php echo $promo; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="lien" class="form-label">Lien (optionnel)</label>
                    <input type="url" class="form-control" id="lien" name="lien" 
                           value="<?php echo htmlspecialchars($ressource['lien'] ?? ''); ?>">
                </div>
                
                <div class="mb-3">
                    <label for="fichier" class="form-label">
                        <?php echo $isEdit ? 'Nouveau fichier (optionnel)' : 'Fichier (optionnel)'; ?>
                    </label>
                    <input type="file" class="form-control" id="fichier" name="fichier">
                    <?php if ($isEdit && !empty($ressource['fichier'])): ?>
                        <small class="form-text text-muted">
                            Fichier actuel : <?php echo htmlspecialchars($ressource['fichier']); ?>
                        </small>
                    <?php endif; ?>
                    <small class="form-text text-muted d-block">
                        Formats acceptés : PDF, DOC, DOCX, TXT, ZIP, RAR
                    </small>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <?php echo $isEdit ? 'Modifier' : 'Ajouter'; ?> la ressource
                </button>
            </form>
        </div>
    </div>
</div>