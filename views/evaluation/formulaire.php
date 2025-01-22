<?php
$error = $data['error'] ?? '';
$projets = $data['projets'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Créer une évaluation</h2>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?module=evaluation&action=create">
                <div class="form-group mb-3">
                    <label for="id_projet">Projet *</label>
                    <select class="form-control" id="id_projet" name="id_projet" required>
                        <option value="">Sélectionner un projet</option>
                        <?php foreach ($projets as $projet): ?>
                            <option value="<?php echo htmlspecialchars($projet['id_projet']); ?>">
                                <?php echo htmlspecialchars($projet['titre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="titre">Titre de l'évaluation *</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group mb-3">
                    <label for="coefficient">Coefficient</label>
                    <input type="number" class="form-control" id="coefficient" name="coefficient" value="1" min="0" step="0.5">
                </div>
                
                <div class="form-group mb-3">
                    <label for="type">Type d'évaluation *</label>
                    <select class="form-control" id="type" name="type" required>
                        <option value="groupe">Groupe</option>
                        <option value="individuel">Individuel</option>
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="id_rendu">Lier à un rendu (optionnel)</label>
                    <select class="form-control" id="id_rendu" name="id_rendu">
                        <option value="">Aucun rendu</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Créer l'évaluation</button>
                <a href="index.php?module=evaluation&action=list" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('id_projet').addEventListener('change', function() {
    const id_projet = this.value;
    const renduSelect = document.getElementById('id_rendu');
    
    // Vider la liste des rendus
    renduSelect.innerHTML = '<option value="">Aucun rendu</option>';
    
    if (id_projet) {
        // Charger les rendus du projet
        fetch(`index.php?module=rendu&action=getRendus&id_projet=${id_projet}`)
            .then(response => response.json())
            .then(rendus => {
                rendus.forEach(rendu => {
                    const option = document.createElement('option');
                    option.value = rendu.id_rendu;
                    option.textContent = `Rendu du ${new Date(rendu.date_creation).toLocaleDateString()}`;
                    renduSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erreur:', error));
    }
});
</script>