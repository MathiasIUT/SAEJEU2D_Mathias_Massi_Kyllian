-- Table des rendus de projet
CREATE TABLE rendu_projet (
    id_rendu INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    id_groupe INT NOT NULL,
    fichiers JSON NOT NULL,
    commentaire_etudiant TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des évaluations
CREATE TABLE evaluation (
    id_evaluation INT AUTO_INCREMENT PRIMARY KEY,
    id_rendu INT NOT NULL,
    id_evaluateur INT NOT NULL,
    note DECIMAL(4,2),
    commentaire TEXT,
    date_evaluation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_rendu) REFERENCES rendu_projet(id_rendu) ON DELETE CASCADE,
    FOREIGN KEY (id_evaluateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index pour optimiser les performances
CREATE INDEX idx_rendu_projet_groupe ON rendu_projet(id_groupe);
CREATE INDEX idx_evaluation_rendu ON evaluation(id_rendu);
CREATE INDEX idx_evaluation_evaluateur ON evaluation(id_evaluateur);