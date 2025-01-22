<?php
$rendus = $data['rendus'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Liste des rendus</h2>
            <?php if ($_SESSION['user']['role'] === 'etudiant'): ?>
                <a href="index.php?module=rendu&action=submit" class="btn btn-primary">Nouveau rendu</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Projet</th>
                            <th>Groupe</th>
                            <?php if ($_SESSION['user']['role'] !== 'etudiant'): ?>
                                <th>Étudiants</th>
                            <?php endif; ?>
                            <th>Date de rendu</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rendus)): ?>
                            <?php foreach ($rendus as $rendu): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rendu['projet_titre'] ?? 'Projet inconnu'); ?></td>
                                <td><?php echo htmlspecialchars($rendu['groupe_titre'] ?? 'Groupe inconnu'); ?></td>
                                <?php if ($_SESSION['user']['role'] !== 'etudiant'): ?>
                                    <td><?php echo htmlspecialchars($rendu['etudiants'] ?? 'Aucun étudiant associé'); ?></td>
                                <?php endif; ?>
                                <td>
                                    <?php echo isset($rendu['date_creation']) 
                                        ? htmlspecialchars(date('d/m/Y H:i', strtotime($rendu['date_creation'])))
                                        : 'Date inconnue'; ?>
                                </td>
                                <td>
                                    <?php if (isset($rendu['note']) && $rendu['note'] !== null): ?>
                                        <?php echo number_format($rendu['note'], 2); ?>/20
                                        <?php if (!empty($rendu['commentaire'])): ?>
                                            <i class="text-muted" title="<?php echo htmlspecialchars($rendu['commentaire']); ?>">
                                                (voir commentaire)
                                            </i>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Non évalué
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="index.php?module=rendu&action=download&id=<?php echo $rendu['id_rendu'] ?? 0; ?>" 
                                           class="btn btn-sm btn-primary">Télécharger</a>
                                        <?php if ($_SESSION['user']['role'] === 'enseignant' || $_SESSION['user']['role'] === 'admin'): ?>
                                            <a href="index.php?module=rendu&action=evaluate&id=<?php echo $rendu['id_rendu'] ?? 0; ?>" 
                                               class="btn btn-sm btn-warning">Évaluer</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo ($_SESSION['user']['role'] !== 'etudiant') ? '6' : '5'; ?>" class="text-center">
                                    Aucun rendu disponible.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>