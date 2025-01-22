<?php
$evaluation = $data['evaluation'] ?? null;
$enseignants = $data['enseignants'] ?? [];
?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h2>Déléguer l'évaluation</h2>
        </div>
        <div class="card-body">
            <h4><?php echo htmlspecialchars($evaluation['titre']); ?></h4>
            <p>Projet : <?php echo htmlspecialchars($evaluation['projet_titre']); ?></p>
            
            <form method="POST" action="index.php?module=evaluation&action=delegate">
                <input type="hidden" name="id_evaluation" value="<?php echo $evaluation['id_evaluation']; ?>">
                
                <div class="form-group mb-3">
                    <label for="id_enseignant">Déléguer à :</label>
                    <select class="form-control" id="id_enseignant" name="id_enseignant" required>
                        <option value="">Sélectionner un enseignant</option>
                        <?php foreach ($enseignants as $enseignant): ?>
                            <option value="<?php echo $enseignant['id']; ?>">
                                <?php echo htmlspecialchars($enseignant['prenom'] . ' ' . $enseignant['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Déléguer l'évaluation</button>
                <a href="index.php?module=evaluation&action=list" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>