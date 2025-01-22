<?php
$mes_projets = $data['mes_projets'] ?? [];
$derniers_rendus = $data['derniers_rendus'] ?? [];
$total_etudiants = $data['total_etudiants'] ?? 0;
?>
<div class="container mt-4">
    <h1 class="mb-4">Tableau de bord enseignant</h1>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Mes projets</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Semestre</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mes_projets as $projet): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($projet['titre']); ?></td>
                                    <td><?php echo htmlspecialchars($projet['semestre']); ?></td>
                                    <td>
                                        <a href="index.php?module=projet&action=edit&id=<?php echo $projet['id_projet']; ?>" 
                                           class="btn btn-sm btn-warning">Modifier</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Derniers rendus</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Projet</th>
                                    <th>Étudiant</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($derniers_rendus as $rendu): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($rendu['projet_titre']); ?></td>
                                    <td><?php echo htmlspecialchars($rendu['etudiant_login']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($rendu['date_rendu'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>