<?php
$projet = $data['projet'] ?? null;
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2><?php echo htmlspecialchars($projet['titre'] ?? 'Projet inconnu'); ?></h2>
        </div>
        <div class="card-body">
            <p><strong>Description :</strong> <?php echo nl2br(htmlspecialchars($projet['description'] ?? 'Aucune description.')); ?></p>
            <p><strong>Année :</strong> <?php echo htmlspecialchars($projet['annee'] ?? 'Non spécifiée'); ?></p>
            <p><strong>Semestre :</strong> <?php echo htmlspecialchars($projet['semestre'] ?? 'Non spécifié'); ?></p>
        </div>
    </div>
</div>
