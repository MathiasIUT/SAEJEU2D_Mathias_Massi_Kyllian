<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des SAE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">Gestion des SAE</a>
            <?php if (isset($_SESSION['user'])): ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
    <li class="nav-item">
        <a class="nav-link" href="index.php?module=dashboard">Tableau de bord</a>
    </li>
    <?php if (in_array($_SESSION['user']['role'], ['admin', 'enseignant'])): ?>
        <li class="nav-item">
            <a class="nav-link" href="index.php?module=projet&action=list">Projets</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php?module=evaluation&action=list">Évaluations</a>
        </li>
    <?php endif; ?>
    <li class="nav-item">
        <a class="nav-link" href="index.php?module=rendu&action=list">Rendus</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?module=ressource&action=list">Ressources</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?module=message&action=list">Messages</a>
    </li>
</ul>

                    <div class="navbar-nav">
                        <span class="nav-item nav-link text-light">
                            <?php echo htmlspecialchars($_SESSION['user']['login']); ?> 
                            (<?php echo htmlspecialchars($_SESSION['user']['role']); ?>)
                        </span>
                        <a class="nav-link" href="index.php?module=connexion&action=logout">Déconnexion</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="container mt-3">
            <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?> alert-dismissible fade show">
                <?php echo $_SESSION['flash']['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <main class="container mt-4">
        <?php echo $content; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>