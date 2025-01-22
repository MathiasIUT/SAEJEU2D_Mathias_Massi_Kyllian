<?php if (!empty($content)): ?>
    <?php echo $content; ?>
<?php else: ?>
    <div class="container mt-4">
        <div class="alert alert-danger">
            Erreur lors du chargement de la liste des projets.
        </div>
    </div>
<?php endif; ?>