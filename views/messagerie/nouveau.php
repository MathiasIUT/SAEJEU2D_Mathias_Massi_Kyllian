<?php
$error = $data['error'] ?? '';
$utilisateurs = $data['utilisateurs'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Nouveau message</h2>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="destinataire" class="form-label">Destinataire</label>
                    <select class="form-select" id="destinataire" name="destinataire" required>
                        <option value="">Choisir un destinataire</option>
                        <?php foreach ($utilisateurs as $utilisateur): ?>
                        <option value="<?php echo $utilisateur['id']; ?>">
                            <?php echo htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom'] . 
                                  ' (' . $utilisateur['role'] . ')'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="sujet" class="form-label">Sujet</label>
                    <input type="text" class="form-control" id="sujet" name="sujet" required>
                </div>
                
                <div class="mb-3">
                    <label for="contenu" class="form-label">Message</label>
                    <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Envoyer</button>
                <a href="index.php?module=messagerie" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>