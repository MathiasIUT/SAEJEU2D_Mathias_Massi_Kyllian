<?php
$projet = $data['projet'] ?? null;
if (!$projet) {
    echo '<div class="alert alert-danger">Aucun projet trouvé.</div>';
    return;
}
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Confirmer la suppression du projet : <?php echo htmlspecialchars($projet['titre']); ?></h2>
        </div>
        <div class="card-body">
            <p class="text-danger">Attention : Cette action est irréversible. Êtes-vous sûr de vouloir continuer ?</p>
            <p><strong>Description :</strong> <?php echo htmlspecialchars($projet['description']); ?></p>
        </div>
        <div class="card-footer">
            <form method="POST" action="index.php?module=projet&action=delete&id=<?php echo $projet['id_projet']; ?>">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="index.php?module=projet&action=list" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>
