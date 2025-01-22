<?php
$soutenances = $data['soutenances'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Liste des soutenances</h2>
            <?php if ($_SESSION['user']['role'] === 'enseignant' || $_SESSION['user']['role'] === 'admin'): ?>
                <a href="index.php?module=soutenance&action=create" class="btn btn-primary">Nouvelle soutenance</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (empty($soutenances)): ?>
                <div class="alert alert-info">
                    Aucune soutenance n'est planifiée pour le moment.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Date</th>
                                <th>Salle</th>
                                <th>Durée</th>
                                <?php if ($_SESSION['user']['role'] === 'etudiant'): ?>
                                    <th>Heure de passage</th>
                                <?php else: ?>
                                    <th>Passages</th>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($soutenances as $soutenance): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($soutenance['projet_titre']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($soutenance['date_soutenance'])); ?></td>
                                <td><?php echo htmlspecialchars($soutenance['salle']); ?></td>
                                <td><?php echo $soutenance['duree']; ?> minutes</td>
                                <?php if ($_SESSION['user']['role'] === 'etudiant'): ?>
                                    <td>
                                        <?php echo $soutenance['heure_passage']; ?>
                                        (Groupe: <?php echo htmlspecialchars($soutenance['groupe_titre']); ?>)
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <?php if (!empty($soutenance['passages'])): ?>
                                            <ul class="list-unstyled mb-0">
                                                <?php foreach ($soutenance['passages'] as $passage): ?>
                                                <li>
                                                    <?php echo $passage['heure_passage']; ?> - 
                                                    <?php echo htmlspecialchars($passage['groupe_titre']); ?>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span class="text-muted">Aucun passage planifié</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?module=soutenance&action=planifier&id=<?php echo $soutenance['id_soutenance']; ?>" 
                                               class="btn btn-sm btn-warning">Planifier</a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="if(confirm('Supprimer cette soutenance ?')) 
                                                            window.location.href='index.php?module=soutenance&action=supprimer&id=<?php echo $soutenance['id_soutenance']; ?>'">
                                                Supprimer
                                            </button>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>