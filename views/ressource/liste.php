<?php
$ressources = $data['ressources'] ?? [];
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Liste des ressources</h2>
        <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['enseignant', 'admin'])): ?>
            <a href="index.php?module=ressource&action=add" class="btn btn-primary">Ajouter une ressource</a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (empty($ressources)): ?>
            <p class="text-center">Aucune ressource disponible.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Promotion</th>
                            <th>Auteur</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ressources as $ressource): ?>
                            <tr <?php echo $ressource['mise_en_avant'] ? 'class="table-warning"' : ''; ?>>
                                <td>
                                    <?php if ($ressource['mise_en_avant']): ?>
                                        <span class="badge bg-warning me-2">
                                            <i class="fas fa-star"></i> Mise en avant
                                        </span>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($ressource['titre']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($ressource['type']); ?></td>
                                <td><?php echo htmlspecialchars($ressource['description']); ?></td>
                                <td><?php echo htmlspecialchars($ressource['promotion']); ?></td>
                                <td>
                                    <?php 
                                    echo htmlspecialchars($ressource['auteur_prenom'] . ' ' . $ressource['auteur_nom']); 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    echo date('d/m/Y H:i', strtotime($ressource['date_creation'])); 
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($ressource['fichier']): ?>
                                            <a href="uploads/ressources/<?php echo htmlspecialchars($ressource['fichier']); ?>" 
                                               class="btn btn-sm btn-primary" target="_blank">
                                                Télécharger
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($ressource['lien']): ?>
                                            <a href="<?php echo htmlspecialchars($ressource['lien']); ?>" 
                                               class="btn btn-sm btn-info" target="_blank">
                                                Voir le lien
                                            </a>
                                        <?php endif; ?>
                                        <?php if (isset($_SESSION['user']) && ($_SESSION['user']['role'] === 'enseignant' || $_SESSION['user']['role'] === 'admin')): ?>
                                            <a href="index.php?module=ressource&action=edit&id=<?php echo $ressource['id_ressource']; ?>" 
                                               class="btn btn-sm btn-warning">
                                                Modifier
                                            </a>
                                            <a href="index.php?module=ressource&action=delete&id=<?php echo $ressource['id_ressource']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?');">
                                                Supprimer
                                            </a>
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