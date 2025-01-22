<?php if (!empty($content)): ?>
    <?php echo $content; ?>
<?php else: ?>
    <div class="container mt-4">
        <div class="alert alert-danger">
            Aucun contenu à afficher pour le tableau de bord.
        </div>
    </div>
<?php endif; ?>