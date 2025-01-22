<?php
$message = $data['message'] ?? null;
if (!$message) {
    header('Location: index.php?module=messagerie');
    exit;
}
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2><?php echo htmlspecialchars($message['sujet']); ?></h2>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <strong>De:</strong> <?php echo htmlspecialchars($message['expediteur_login']); ?><br>
                <strong>À:</strong> <?php echo htmlspecialchars($message['destinataire_login']); ?><br>
                <strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($message['date_envoi'])); ?>
            </div>
            
            <div class="message-content p-3 bg-light rounded">
                <?php echo nl2br(htmlspecialchars($message['contenu'])); ?>
            </div>
            
            <div class="mt-4">
                <a href="index.php?module=messagerie&action=nouveau&reply_to=<?php echo $message['id_message']; ?>" 
                   class="btn btn-primary">Répondre</a>
                <a href="index.php?module=messagerie" class="btn btn-secondary">Retour</a>
                <a href="index.php?module=messagerie&action=supprimer&id=<?php echo $message['id_message']; ?>" 
                   class="btn btn-danger float-end"
                   onclick="return confirm('Supprimer ce message ?')">
                    Supprimer
                </a>
            </div>
        </div>
    </div>
</div>