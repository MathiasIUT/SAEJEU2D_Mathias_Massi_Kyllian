<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Connexion</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="index.php?module=connexion&action=login">
                        <div class="mb-3">
                            <label for="login" class="form-label">Identifiant</label>
                            <input type="text" class="form-control" id="login" name="login" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                            <a href="index.php?module=connexion&action=register" class="btn btn-outline-secondary">S'inscrire</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>