<?php
$evaluation = $data['evaluation'] ?? null;
$etudiants = $data['etudiants'] ?? [];
?>
<div class="card">
    <div class="card-header">
        <h2>Noter l'évaluation</h2>
    </div>
    <div class="card-body">
        <h4><?php echo htmlspecialchars($evaluation['titre']); ?></h4>
        <p>Projet : <?php echo htmlspecialchars($evaluation['projet_titre']); ?></p>
        
        <form method="POST" action="index.php?module=evaluation&action=note">
            <input type="hidden" name="id_evaluation" value="<?php echo $evaluation['id_evaluation']; ?>">
            
            <?php if (empty($etudiants)): ?>
                <div class="alert alert-info">
                    Aucun étudiant à évaluer pour cette évaluation.
                </div>
            <?php else: ?>
                <?php foreach ($etudiants as $etudiant): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5><?php echo htmlspecialchars($etudiant['prenom'] . ' ' . $etudiant['nom']); ?></h5>
                            
                            <div class="form-group mb-3">
                                <label for="note_<?php echo $etudiant['id']; ?>">Note :</label>
                                <input type="number" class="form-control" 
                                       id="note_<?php echo $etudiant['id']; ?>" 
                                       name="notes[<?php echo $etudiant['id']; ?>]" 
                                       min="0" max="20" step="0.5"
                                       value="<?php echo $etudiant['note'] ?? ''; ?>"
                                       required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="commentaire_<?php echo $etudiant['id']; ?>">Commentaire :</label>
                                <textarea class="form-control" 
                                          id="commentaire_<?php echo $etudiant['id']; ?>" 
                                          name="commentaires[<?php echo $etudiant['id']; ?>]"
                                          rows="3"><?php echo htmlspecialchars($etudiant['commentaire'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer les notes</button>
                    <a href="index.php?module=evaluation&action=list" class="btn btn-secondary">Annuler</a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>