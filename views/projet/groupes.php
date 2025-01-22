<?php
$groupes = $data['groupes'] ?? [];
$ressources = $data['ressources'] ?? [];
$evaluations = $data['evaluations'] ?? [];
$rendus = $data['rendus'] ?? [];
?>
<div class="container">
    <!-- Section Projets et Groupes -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>Mes Projets et Groupes</h2>
        </div>
        <div class="card-body">
            <?php if (empty($groupes)): ?>
                <p class="text-center">Vous n'êtes inscrit dans aucun groupe pour le moment.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($groupes as $groupe): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($groupe['projet_titre']); ?></h5>
                                    <?php if ($groupe['groupe_modifiable']): ?>
                                        <span class="badge bg-info">Groupe modifiable</span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        Groupe : <?php echo htmlspecialchars($groupe['groupe_titre']); ?>
                                    </h6>
                                    <p class="card-text">
                                        <?php echo nl2br(htmlspecialchars($groupe['projet_description'])); ?>
                                    </p>
                                    
                                    <!-- Champs du projet -->
                                    <?php if (!empty($groupe['champs'])): ?>
                                        <div class="mb-3">
                                            <h6>Informations du projet :</h6>
                                            <dl class="row">
                                                <?php foreach ($groupe['champs'] as $champ): ?>
                                                    <dt class="col-sm-4"><?php echo htmlspecialchars($champ['nom']); ?></dt>
                                                    <dd class="col-sm-8">
                                                        <?php if ($champ['modifiable_etudiant']): ?>
                                                            <form method="POST" action="index.php?module=projet&action=update-field" class="d-flex">
                                                                <input type="hidden" name="id_champ" value="<?php echo $champ['id_champ']; ?>">
                                                                <input type="<?php echo $champ['type']; ?>" 
                                                                       class="form-control form-control-sm me-2" 
                                                                       name="valeur" 
                                                                       value="<?php echo htmlspecialchars($champ['valeur'] ?? ''); ?>">
                                                                <button type="submit" class="btn btn-sm btn-primary">✓</button>
                                                            </form>
                                                        <?php else: ?>
                                                            <?php echo htmlspecialchars($champ['valeur'] ?? ''); ?>
                                                        <?php endif; ?>
                                                    </dd>
                                                <?php endforeach; ?>
                                            </dl>
                                        </div>
                                    <?php endif; ?>

                                    <h6>Membres du groupe :</h6>
                                    <ul class="list-unstyled">
                                        <?php 
                                        $membres = explode(',', $groupe['membres']);
                                        foreach ($membres as $membre): 
                                        ?>
                                            <li><?php echo htmlspecialchars($membre); ?></li>
                                        <?php endforeach; ?>
                                    </ul>

                                    <!-- Actions rapides -->
                                    <div class="mt-3">
                                        <a href="index.php?module=rendu&action=submit&projet=<?php echo $groupe['id_projet']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            Soumettre un rendu
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section Évaluations -->
    <div class="card mb-4">
        <div class="card-header">
            <h2>Mes Évaluations</h2>
        </div>
        <div class="card-body">
            <?php if (empty($evaluations)): ?>
                <p class="text-center">Aucune évaluation disponible.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($evaluations as $evaluation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($evaluation['projet_titre']); ?></td>
                                    <td><?php echo htmlspecialchars($evaluation['titre']); ?></td>
                                    <td><?php echo htmlspecialchars($evaluation['type']); ?></td>
                                    <td>
                                        <?php if (isset($evaluation['note'])): ?>
                                            <?php echo number_format($evaluation['note'], 2); ?>/20
                                        <?php else: ?>
                                            Non évalué
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($evaluation['commentaire'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Section Ressources -->
    <div class="card">
        <div class="card-header">
            <h2>Ressources Disponibles</h2>
        </div>
        <div class="card-body">
            <?php if (empty($ressources)): ?>
                <p class="text-center">Aucune ressource disponible pour le moment.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Projet</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ressources as $ressource): ?>
                                <tr <?php echo $ressource['mise_en_avant'] ? 'class="table-warning"' : ''; ?>>
                                    <td>
                                        <?php if ($ressource['mise_en_avant']): ?>
                                            <span class="badge bg-warning me-2">
                                                <i class="fas fa-star"></i> Important
                                            </span>
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($ressource['titre']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($ressource['type']); ?></td>
                                    <td><?php echo htmlspecialchars($ressource['description']); ?></td>
                                    <td>
                                        <?php echo $ressource['projet_titre'] 
                                            ? htmlspecialchars($ressource['projet_titre'])
                                            : 'Ressource générale'; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <?php if ($ressource['fichier']): ?>
                                                <a href="uploads/ressources/<?php echo htmlspecialchars($ressource['fichier']); ?>" 
                                                   class="btn btn-sm btn-primary" 
                                                   target="_blank">
                                                    Télécharger
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($ressource['lien']): ?>
                                                <a href="<?php echo htmlspecialchars($ressource['lien']); ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   target="_blank">
                                                    Voir le lien
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
</div>
