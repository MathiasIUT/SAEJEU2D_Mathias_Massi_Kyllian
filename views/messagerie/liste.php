<?php
$messages = $data['messages'] ?? [];
$id_utilisateur = $_SESSION['user']['id'];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Messages</h2>
            <a href="index.php?module=messagerie&action=nouveau" class="btn btn-primary">
                Nouveau message
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>État</th>
                            <th>De</th>
                            <th>À</th>
                            <th>Sujet</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                        <tr class="<?php echo (!$message['lu'] && $message['id_destinataire'] == $id_utilisateur) ? 'table-primary' : ''; ?>">
                            <td>
                                <?php if ($message['id_destinataire'] == $id_utilisateur): ?>
                                    <?php if ($message['lu']): ?>
                                        <span class="badge bg-secondary">Lu</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Nouveau</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-info">Envoyé</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($message['expediteur_login']); ?></td>
                            <td><?php echo htmlspecialchars($message['destinataire_login']); ?></td>
                            <td>
                                <a href="index.php?module=messagerie&action=lire&id=<?php echo $message['id_message']; ?>" 
                                   class="text-decoration-none">
                                    <?php echo htmlspecialchars($message['sujet']); ?>
                                </a>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($message['date_envoi'])); ?></td>
                            <td>
                                <a href="index.php?module=messagerie&action=supprimer&id=<?php echo $message['id_message']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Supprimer ce message ?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>