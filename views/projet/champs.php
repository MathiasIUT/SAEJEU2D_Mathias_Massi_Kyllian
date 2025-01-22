<?php
$projet = $data['projet'] ?? null;
$champs = $data['champs'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Gestion des champs du projet</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFieldModal">
                Ajouter un champ
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom du champ</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Modifiable par les étudiants</th>
                            <th>Valeur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($champs as $champ): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($champ['nom']); ?></td>
                            <td><?php echo htmlspecialchars($champ['description']); ?></td>
                            <td><?php echo htmlspecialchars($champ['type']); ?></td>
                            <td>
                                <span class="badge <?php echo $champ['modifiable_etudiant'] ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $champ['modifiable_etudiant'] ? 'Oui' : 'Non'; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($champ['valeur']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-warning" 
                                            onclick="editField(<?php echo htmlspecialchars(json_encode($champ)); ?>)">
                                        Modifier
                                    </button>
                                    <a href="index.php?module=projet&action=delete-field&id=<?php echo $champ['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce champ ?')">
                                        Supprimer
                                    </a>
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

<!-- Modal d'ajout de champ -->
<div class="modal fade" id="addFieldModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="index.php?module=projet&action=add-field">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un champ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_projet" value="<?php echo $projet['id_projet']; ?>">
                    
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du champ</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Type de champ</label>
                        <select class="form-control" id="type" name="type" required>
                            <option value="text">Texte</option>
                            <option value="url">URL</option>
                            <option value="date">Date</option>
                            <option value="number">Nombre</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modifiable_etudiant" name="modifiable_etudiant">
                            <label class="form-check-label" for="modifiable_etudiant">
                                Modifiable par les étudiants
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="valeur" class="form-label">Valeur par défaut</label>
                        <input type="text" class="form-control" id="valeur" name="valeur">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editField(field) {
    // Créer une copie du modal d'ajout et l'adapter pour la modification
    const editModal = document.querySelector('#addFieldModal').cloneNode(true);
    editModal.id = 'editFieldModal';
    editModal.querySelector('form').action = 'index.php?module=projet&action=edit-field&id=' + field.id;
    editModal.querySelector('.modal-title').textContent = 'Modifier le champ';
    editModal.querySelector('button[type="submit"]').textContent = 'Modifier';
    
    // Remplir les champs avec les valeurs existantes
    editModal.querySelector('#nom').value = field.nom;
    editModal.querySelector('#description').value = field.description;
    editModal.querySelector('#type').value = field.type;
    editModal.querySelector('#modifiable_etudiant').checked = field.modifiable_etudiant;
    editModal.querySelector('#valeur').value = field.valeur;
    
    // Ajouter le modal au document et l'afficher
    document.body.appendChild(editModal);
    new bootstrap.Modal(editModal).show();
    
    // Nettoyer le modal après sa fermeture
    editModal.addEventListener('hidden.bs.modal', function() {
        editModal.remove();
    });
}
</script>