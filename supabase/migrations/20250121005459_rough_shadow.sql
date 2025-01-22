-- Table des soutenances
CREATE TABLE soutenance (
    id_soutenance INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    date_soutenance DATE NOT NULL,
    duree INT NOT NULL,
    salle VARCHAR(50) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison soutenance-groupe
CREATE TABLE soutenance_groupe (
    id_soutenance INT NOT NULL,
    id_groupe INT NOT NULL,
    heure_passage TIME NOT NULL,
    PRIMARY KEY (id_soutenance, id_groupe),
    FOREIGN KEY (id_soutenance) REFERENCES soutenance(id_soutenance) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ajout des index pour optimiser les performances
CREATE INDEX idx_soutenance_projet ON soutenance(id_projet);
CREATE INDEX idx_soutenance_date ON soutenance(date_soutenance);
CREATE INDEX idx_soutenance_groupe_heure ON soutenance_groupe(heure_passage);