<?php
$projets = $data['projets'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Liste des projets</h2>
            <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['enseignant', 'admin'])): ?>
                <a href="index.php?module=projet&action=create" class="btn btn-primary">Nouveau projet</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($projets)): ?>
                <p class="text-center">Aucun projet disponible.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Semestre</th>
                                <th>Responsable</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projets as $projet): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($projet['titre']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($projet['description'] ?? '', 0, 100)) . '...'; ?></td>
                                    <td><?php echo htmlspecialchars($projet['semestre']); ?></td>
                                    <td>
                                        <?php 
                                        $responsable = '';
                                        if (isset($projet['responsable_prenom']) && isset($projet['responsable_nom'])) {
                                            $responsable = $projet['responsable_prenom'] . ' ' . $projet['responsable_nom'];
                                        }
                                        echo htmlspecialchars($responsable); 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?module=projet&action=details&id=<?php echo $projet['id_projet']; ?>" 
                                               class="btn btn-sm btn-info">Détails</a>
                                            
                                            <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'enseignant' || $_SESSION['user']['role'] === 'admin')): ?>
                                                <a href="index.php?module=projet&action=edit&id=<?php echo $projet['id_projet']; ?>" 
                                                   class="btn btn-sm btn-warning">Modifier</a>
                                                
                                                <a href="index.php?module=projet&action=soutenances&id=<?php echo $projet['id_projet']; ?>" 
                                                   class="btn btn-sm btn-primary">Soutenances</a>
                                                
                                                <?php if ($_SESSION['user']['role'] === 'admin' || $projet['id_responsable'] === $_SESSION['user']['id']): ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteModal<?php echo $projet['id_projet']; ?>">
                                                        Supprimer
                                                    </button>
                                                    
                                                    <!-- Modal de confirmation de suppression -->
                                                    <div class="modal fade" id="deleteModal<?php echo $projet['id_projet']; ?>" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Êtes-vous sûr de vouloir supprimer le projet "<?php echo htmlspecialchars($projet['titre']); ?>" ?</p>
                                                                    <p class="text-danger">
                                                                        <strong>Attention :</strong> Cette action est irréversible et supprimera :
                                                                    </p>
                                                                    <ul class="text-danger">
                                                                        <li>Tous les rendus associés</li>
                                                                        <li>Toutes les évaluations</li>
                                                                        <li>Tous les groupes et leurs compositions</li>
                                                                        <li>Toutes les soutenances planifiées</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                    <a href="index.php?module=projet&action=delete&id=<?php echo $projet['id_projet']; ?>" 
                                                                       class="btn btn-danger">Confirmer la suppression</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
