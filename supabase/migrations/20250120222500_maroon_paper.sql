/*
  # Ajout des tables pour la gestion des groupes et champs personnalisés

  1. Nouvelles Tables
    - `groupe_projet`: Gère les groupes d'étudiants par projet
      - `id_groupe` (int, primary key)
      - `id_projet` (int, foreign key)
      - `titre` (varchar)
      - `titre_modifiable` (boolean)
      - `image_modifiable` (boolean)
      - `image` (varchar, nullable)
      - `date_creation` (timestamp)

    - `groupe_etudiant`: Liaison entre groupes et étudiants
      - `id_groupe` (int, foreign key)
      - `id_etudiant` (int, foreign key)
      - `date_ajout` (timestamp)

    - `champ_projet`: Champs personnalisés des projets
      - `id_champ` (int, primary key)
      - `id_projet` (int, foreign key)
      - `nom` (varchar)
      - `description` (text)
      - `type` (enum: 'text', 'url', 'file')
      - `obligatoire` (boolean)
      - `modifiable_groupe` (boolean)
      - `ordre` (int)

    - `valeur_champ`: Valeurs des champs personnalisés
      - `id_champ` (int, foreign key)
      - `id_groupe` (int, foreign key, nullable)
      - `valeur` (text)
      - `date_modification` (timestamp)
*/

-- Table des groupes de projet
CREATE TABLE IF NOT EXISTS groupe_projet (
    id_groupe INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    titre_modifiable BOOLEAN DEFAULT false,
    image_modifiable BOOLEAN DEFAULT false,
    image VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison groupes-étudiants
CREATE TABLE IF NOT EXISTS groupe_etudiant (
    id_groupe INT NOT NULL,
    id_etudiant INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_groupe, id_etudiant),
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE,
    FOREIGN KEY (id_etudiant) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des champs personnalisés
CREATE TABLE IF NOT EXISTS champ_projet (
    id_champ INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('text', 'url', 'file') NOT NULL,
    obligatoire BOOLEAN DEFAULT false,
    modifiable_groupe BOOLEAN DEFAULT false,
    ordre INT NOT NULL DEFAULT 0,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des valeurs des champs
CREATE TABLE IF NOT EXISTS valeur_champ (
    id_champ INT NOT NULL,
    id_groupe INT,
    valeur TEXT NOT NULL,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_champ, id_groupe),
    FOREIGN KEY (id_champ) REFERENCES champ_projet(id_champ) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index pour optimiser les performances
CREATE INDEX idx_groupe_projet_projet ON groupe_projet(id_projet);
CREATE INDEX idx_groupe_etudiant_etudiant ON groupe_etudiant(id_etudiant);
CREATE INDEX idx_champ_projet_projet ON champ_projet(id_projet);
CREATE INDEX idx_valeur_champ_groupe ON valeur_champ(id_groupe);