<?php
$notifications = $data['notifications'] ?? [];
$id_projet = $data['id_projet'] ?? 0;
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Créer une notification</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="creer_notification">
                        
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="info">Information</option>
                                <option value="warning">Avertissement</option>
                                <option value="danger">Important</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="destinataires" class="form-label">Destinataires</label>
                            <select class="form-select" id="destinataires" name="destinataires[]" multiple required>
                                <option value="all">Tous les participants</option>
                                <option value="students">Tous les étudiants</option>
                                <option value="teachers">Tous les enseignants</option>
                                <?php foreach ($data['groupes'] ?? [] as $groupe): ?>
                                <option value="group_<?php echo $groupe['id_groupe']; ?>">
                                    Groupe: <?php echo htmlspecialchars($groupe['titre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="email" name="email">
                                <label class="form-check-label" for="email">
                                    Envoyer aussi par email
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Envoyer la notification</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Historique des notifications</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Destinataires</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notifications as $notif): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($notif['date_creation'])); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($notif['titre']); ?>
                                        <small class="d-block text-muted">
                                            <?php echo htmlspecialchars($notif['message']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $notif['type']; ?>">
                                            <?php echo ucfirst($notif['type']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($notif['destinataires']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $notif['lu'] ? 'success' : 'warning'; ?>">
                                            <?php echo $notif['lu'] ? 'Lu' : 'Non lu'; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>