<?php
require_once ROOT_PATH . '/core/View.php';

class Vue_utilisateur extends View {
    public function afficherFormulaire($data = []) {
        $utilisateur = $data['utilisateur'] ?? null;
        $error = $data['error'] ?? '';
        
        ob_start(); ?>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <h2><?php echo $utilisateur ? 'Modifier l\'utilisateur' : 'Créer un utilisateur'; ?></h2>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="login" class="form-label">Login</label>
                            <input type="text" class="form-control" id="login" name="login" value="<?php echo $utilisateur['login'] ?? ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe<?php echo $utilisateur ? ' (laisser vide pour ne pas modifier)' : ''; ?></label>
                            <input type="password" class="form-control" id="password" name="password" <?php echo $utilisateur ? '' : 'required'; ?>>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin" <?php echo ($utilisateur['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                                <option value="enseignant" <?php echo ($utilisateur['role'] ?? '') === 'enseignant' ? 'selected' : ''; ?>>Enseignant</option>
                                <option value="etudiant" <?php echo ($utilisateur['role'] ?? '') === 'etudiant' ? 'selected' : ''; ?>>Étudiant</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $utilisateur ? 'Modifier' : 'Créer'; ?></button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        $this->render('utilisateur/form', ['content' => $content]);
    }
    
    public function afficherListe($utilisateurs) {
        ob_start(); ?>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Liste des utilisateurs</h2>
                    <a href="index.php?module=utilisateur&action=create" class="btn btn-primary">Nouvel utilisateur</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Login</th>
                                    <th>Rôle</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($utilisateurs as $utilisateur): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($utilisateur['login']); ?></td>
                                    <td><?php echo htmlspecialchars($utilisateur['role']); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?module=utilisateur&action=edit&id=<?php echo $utilisateur['id']; ?>" 
                                               class="btn btn-sm btn-warning">Modifier</a>
                                            <a href="index.php?module=utilisateur&action=delete&id=<?php echo $utilisateur['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        $this->render('utilisateur/list', ['content' => $content]);
    }
}