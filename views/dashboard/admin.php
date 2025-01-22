<?php
$total_utilisateurs = $data['total_utilisateurs'] ?? 0;
$total_projets = $data['total_projets'] ?? 0;
$total_rendus = $data['total_rendus'] ?? 0;
$derniers_utilisateurs = $data['derniers_utilisateurs'] ?? [];
?>
<div class="container mt-4">
    <h1 class="mb-4">Tableau de bord administrateur</h1>
    
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text display-4"><?php echo $total_utilisateurs; ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Projets</h5>
                    <p class="card-text display-4"><?php echo $total_projets; ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Rendus</h5>
                    <p class="card-text display-4"><?php echo $total_rendus; ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Derniers utilisateurs inscrits</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Login</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($derniers_utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($utilisateur['login']); ?></td>
                            <td><?php echo htmlspecialchars($utilisateur['role']); ?></td>
                            <td>
                                <a href="index.php?module=utilisateur&action=edit&id=<?php echo $utilisateur['id']; ?>" 
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