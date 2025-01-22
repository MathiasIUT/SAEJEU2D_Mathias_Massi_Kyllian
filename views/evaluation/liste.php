<?php
$evaluations = $data['evaluations'] ?? [];
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Liste des évaluations</h2>
        <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['role'], ['enseignant', 'admin'])): ?>
            <a href="index.php?module=evaluation&action=create" class="btn btn-primary">Créer une évaluation</a>
        <?php endif; ?>
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
                            <th>Coefficient</th>
                            <th>Évaluateur</th>
                            <th>Délégué à</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $evaluation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($evaluation['projet_titre']); ?></td>
                                <td><?php echo htmlspecialchars($evaluation['titre']); ?></td>
                                <td><?php echo htmlspecialchars($evaluation['type']); ?></td>
                                <td><?php echo number_format($evaluation['coefficient'], 1); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($evaluation['evaluateur_nom'] . ' ' . $evaluation['evaluateur_prenom']); ?>
                                </td>
                                <td>
                                    <?php 
                                    if ($evaluation['delegue_nom']) {
                                        echo htmlspecialchars($evaluation['delegue_nom'] . ' ' . $evaluation['delegue_prenom']);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($_SESSION['user']['role'] === 'enseignant' || $_SESSION['user']['role'] === 'admin'): ?>
                                            <a href="index.php?module=evaluation&action=note&id=<?php echo $evaluation['id_evaluation']; ?>" 
                                               class="btn btn-sm btn-primary">Noter</a>
                                            <a href="index.php?module=evaluation&action=delegate&id=<?php echo $evaluation['id_evaluation']; ?>" 
                                               class="btn btn-sm btn-warning">Déléguer</a>
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