<?php
$stats = $data['stats'] ?? [];
$id_projet = $data['id_projet'] ?? 0;
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Groupes</h5>
                    <p class="display-4"><?php echo $stats['nb_groupes'] ?? 0; ?></p>
                    <p class="mb-0">
                        <small><?php echo $stats['nb_etudiants'] ?? 0; ?> étudiants au total</small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Rendus</h5>
                    <p class="display-4"><?php echo $stats['nb_rendus'] ?? 0; ?></p>
                    <p class="mb-0">
                        <small>Note moyenne: <?php echo number_format($stats['moyenne_notes'] ?? 0, 2); ?>/20</small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Ressources</h5>
                    <p class="display-4"><?php echo $stats['nb_ressources'] ?? 0; ?></p>
                    <p class="mb-0">
                        <small><?php echo $stats['nb_ressources_semaine'] ?? 0; ?> cette semaine</small>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Activité</h5>
                    <p class="display-4"><?php echo $stats['nb_actions'] ?? 0; ?></p>
                    <p class="mb-0">
                        <small>Dernière activité: <?php echo $stats['derniere_activite'] ?? 'N/A'; ?></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Progression des groupes</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Groupe</th>
                                    <th>Rendus complétés</th>
                                    <th>Moyenne</th>
                                    <th>Progression</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['progression_groupes'] ?? [] as $groupe): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($groupe['titre']); ?></td>
                                    <td><?php echo $groupe['rendus_completes']; ?>/<?php echo $groupe['total_rendus']; ?></td>
                                    <td><?php echo number_format($groupe['moyenne'], 2); ?>/20</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: <?php echo $groupe['progression']; ?>%"
                                                 aria-valuenow="<?php echo $groupe['progression']; ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <?php echo $groupe['progression']; ?>%
                                            </div>
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
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h3>Activité récente</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($stats['activites_recentes'] ?? [] as $activite): ?>
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <?php echo date('d/m/Y H:i', strtotime($activite['date'])); ?>
                            </div>
                            <div class="timeline-content">
                                <h4><?php echo htmlspecialchars($activite['titre']); ?></h4>
                                <p><?php echo htmlspecialchars($activite['description']); ?></p>
                                <small class="text-muted">
                                    Par <?php echo htmlspecialchars($activite['utilisateur']); ?>
                                </small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Statistiques des compétences</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Compétence</th>
                                    <th>Niveau moyen</th>
                                    <th>Étudiants validés</th>
                                    <th>Distribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['competences'] ?? [] as $competence): ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($competence['code']); ?> - 
                                        <?php echo htmlspecialchars($competence['libelle']); ?>
                                    </td>
                                    <td><?php echo number_format($competence['niveau_moyen'], 1); ?>/3</td>
                                    <td>
                                        <?php echo $competence['nb_valides']; ?>/<?php echo $competence['total_etudiants']; ?>
                                        (<?php echo round(($competence['nb_valides'] / $competence['total_etudiants']) * 100); ?>%)
                                    </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-danger" role="progressbar" 
                                                 style="width: <?php echo $competence['distribution'][1]; ?>%">
                                                N1: <?php echo $competence['distribution'][1]; ?>%
                                            </div>
                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                 style="width: <?php echo $competence['distribution'][2]; ?>%">
                                                N2: <?php echo $competence['distribution'][2]; ?>%
                                            </div>
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?php echo $competence['distribution'][3]; ?>%">
                                                N3: <?php echo $competence['distribution'][3]; ?>%
                                            </div>
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
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 40px;
    margin-bottom: 20px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-date {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
}

.timeline-content h4 {
    margin: 0 0 10px;
    font-size: 1rem;
}

.timeline-content p {
    margin: 0 0 5px;
}
</style>