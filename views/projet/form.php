<?php if (!empty($content)): ?>
    <?php echo $content; ?>
<?php else: ?>
    <div class="container mt-4">
        <div class="alert alert-danger">
            Erreur lors du chargement du formulaire.
        </div>
    </div>
<?php endif; ?>