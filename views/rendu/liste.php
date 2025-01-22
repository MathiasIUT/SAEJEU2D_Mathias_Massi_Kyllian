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
                                <th>Étudiant</th>
                            <?php endif; ?>
                            <th>Date de rendu</th>
                            <th>Note</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rendus as $rendu): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($rendu['projet_titre']); ?></td>
                            <td><?php echo htmlspecialchars($rendu['groupe_titre']); ?></td>
                            <?php if ($_SESSION['user']['role'] !== 'etudiant'): ?>
                                <td><?php echo htmlspecialchars($rendu['etudiant_login']); ?></td>
                            <?php endif; ?>
                            <td><?php echo date('d/m/Y H:i', strtotime($rendu['date_creation'])); ?></td>
                            <td>
                                <?php if ($rendu['note']): ?>
                                    <?php echo number_format($rendu['note'], 2); ?>/20
                                    <?php if ($rendu['commentaire']): ?>
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
                                    <a href="index.php?module=rendu&action=download&id=<?php echo $rendu['id_rendu']; ?>" 
                                       class="btn btn-sm btn-primary">Télécharger</a>
                                    <?php if ($_SESSION['user']['role'] === 'enseignant' || $_SESSION['user']['role'] === 'admin'): ?>
                                        <a href="index.php?module=rendu&action=evaluate&id=<?php echo $rendu['id_rendu']; ?>" 
                                           class="btn btn-sm btn-warning">Évaluer</a>
                                    <?php endif; ?>
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