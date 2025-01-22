<?php
$projet = $data['projet'] ?? null;
$soutenances = $data['soutenances'] ?? [];
$groupes = $data['groupes'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Gestion des soutenances</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSoutenanceModal">
                Planifier une soutenance
            </button>
        </div>
        <div class="card-body">
            <?php if (empty($soutenances)): ?>
                <p class="text-center">Aucune soutenance planifiée.</p>
            <?php else: ?>
                <?php foreach ($soutenances as $soutenance): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    Soutenance du <?php echo date('d/m/Y', strtotime($soutenance['date_soutenance'])); ?>
                                </h5>
                                <div>
                                    <button type="button" class="btn btn-sm btn-info" 
                                            onclick="planifierPassage(<?php echo $soutenance['id_soutenance']; ?>)">
                                        Ajouter un passage
                                    </button>
                                    <a href="index.php?module=projet&action=delete-soutenance&id=<?php echo $soutenance['id_soutenance']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette soutenance ?')">
                                        Supprimer
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p><strong>Salle :</strong> <?php echo htmlspecialchars($soutenance['salle']); ?></p>
                            <p><strong>Durée par groupe :</strong> <?php echo $soutenance['duree']; ?> minutes</p>
                            
                            <?php if (!empty($soutenance['passages'])): ?>
                                <h6>Planning des passages :</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Heure</th>
                                            <th>Groupe</th>
                                            <th>Note</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($soutenance['passages'] as $passage): ?>
                                            <tr>
                                                <td><?php echo date('H:i', strtotime($passage['heure_passage'])); ?></td>
                                                <td><?php echo htmlspecialchars($passage['groupe_titre']); ?></td>
                                                <td>
                                                    <?php if (isset($passage['note'])): ?>
                                                        <?php echo number_format($passage['note'], 2); ?>/20
                                                    <?php else: ?>
                                                        Non évalué
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                            onclick="evaluerPassage(<?php echo htmlspecialchars(json_encode($passage)); ?>)">
                                                        Évaluer
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="text-muted">Aucun passage planifié</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal d'ajout de soutenance -->
<div class="modal fade" id="addSoutenanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="index.php?module=projet&action=add-soutenance">
                <div class="modal-header">
                    <h5 class="modal-title">Planifier une soutenance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_projet" value="<?php echo $projet['id_projet']; ?>">
                    
                    <div class="mb-3">
                        <label for="date_soutenance" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date_soutenance" name="date_soutenance" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="salle" class="form-label">Salle</label>
                        <input type="text" class="form-control" id="salle" name="salle" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="duree" class="form-label">Durée par groupe (en minutes)</label>
                        <input type="number" class="form-control" id="duree" name="duree" value="20" min="5" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Planifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de planification de passage -->
<div class="modal fade" id="planifierPassageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="index.php?module=projet&action=add-passage">
                <div class="modal-header">
                    <h5 class="modal-title">Planifier un passage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_soutenance" id="id_soutenance_passage">
                    
                    <div class="mb-3">
                        <label for="id_groupe" class="form-label">Groupe</label>
                        <select class="form-control" id="id_groupe" name="id_groupe" required>
                            <option value="">Sélectionner un groupe</option>
                            <?php foreach ($groupes as $groupe): ?>
                                <option value="<?php echo $groupe['id_groupe']; ?>">
                                    <?php echo htmlspecialchars($groupe['titre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="heure_passage" class="form-label">Heure de passage</label>
                        <input type="time" class="form-control" id="heure_passage" name="heure_passage" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Planifier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'évaluation -->
<div class="modal fade" id="evaluerPassageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="index.php?module=projet&action=evaluer-passage">
                <div class="modal-header">
                    <h5 class="modal-title">Évaluer le passage</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_passage" id="id_passage_eval">
                    
                    <div class="mb-3">
                        <label for="note" class="form-label">Note</label>
                        <input type="number" class="form-control" id="note" name="note" min="0" max="20" step="0.5" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="commentaire" class="form-label">Commentaire</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Évaluer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function planifierPassage(id_soutenance) {
    document.getElementById('id_soutenance_passage').value = id_soutenance;
    new bootstrap.Modal(document.getElementById('planifierPassageModal')).show();
}

function evaluerPassage(passage) {
    document.getElementById('id_passage_eval').value = passage.id_passage;
    if (passage.note) {
        document.getElementById('note').value = passage.note;
    }
    if (passage.commentaire) {
        document.getElementById('commentaire').value = passage.commentaire;
    }
    new bootstrap.Modal(document.getElementById('evaluerPassageModal')).show();
}
</script>