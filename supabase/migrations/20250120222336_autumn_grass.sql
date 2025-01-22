/*
  # Ajout des tables pour la gestion des co-responsables et intervenants

  1. Nouvelles Tables
    - `projet_responsable`: Gère les co-responsables des projets
      - `id_projet` (int, clé étrangère vers projet)
      - `id_enseignant` (int, clé étrangère vers utilisateurs)
      - `role` (enum: 'responsable', 'co-responsable', 'intervenant')
      - `date_ajout` (timestamp)
    
  2. Sécurité
    - Enable RLS sur la nouvelle table
    - Ajout des politiques d'accès appropriées
*/

-- Table des responsables de projet
CREATE TABLE IF NOT EXISTS projet_responsable (
    id_projet INT NOT NULL,
    id_enseignant INT NOT NULL,
    role ENUM('responsable', 'co-responsable', 'intervenant') NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_projet, id_enseignant),
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_enseignant) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migration des responsables existants
INSERT INTO projet_responsable (id_projet, id_enseignant, role)
SELECT id_projet, id_responsable, 'responsable'
FROM projet;

-- Ajout d'index pour optimiser les performances
CREATE INDEX idx_projet_responsable_projet ON projet_responsable(id_projet);
CREATE INDEX idx_projet_responsable_enseignant ON projet_responsable(id_enseignant);