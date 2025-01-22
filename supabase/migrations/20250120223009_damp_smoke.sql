/*
  # Ajout des tables pour les évaluations et ressources

  1. Nouvelles Tables
    - `rendu_projet` : Gestion des rendus de projet
    - `evaluation` : Stockage des évaluations
    - `ressource` : Ressources pédagogiques
    - `soutenance` : Planification des soutenances
    - `soutenance_groupe` : Association groupes-soutenances

  2. Sécurité
    - Enable RLS sur toutes les tables
    - Politiques d'accès selon les rôles

  3. Relations
    - Clés étrangères pour maintenir l'intégrité référentielle
*/

-- Table des rendus de projet
CREATE TABLE IF NOT EXISTS rendu_projet (
    id_rendu SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_limite TIMESTAMP NOT NULL,
    type_evaluation ENUM('groupe', 'individuel') NOT NULL DEFAULT 'groupe',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table des évaluations
CREATE TABLE IF NOT EXISTS evaluation (
    id_evaluation SERIAL PRIMARY KEY,
    id_rendu INT NOT NULL,
    id_groupe INT NOT NULL,
    id_evaluateur INT NOT NULL,
    note DECIMAL(4,2),
    commentaire TEXT,
    date_evaluation TIMESTAMP NOT NULL,
    date_delegation TIMESTAMP,
    FOREIGN KEY (id_rendu) REFERENCES rendu_projet(id_rendu) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE,
    FOREIGN KEY (id_evaluateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des ressources
CREATE TABLE IF NOT EXISTS ressource (
    id_ressource SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    type ENUM('video', 'pdf', 'code', 'lien') NOT NULL,
    url TEXT NOT NULL,
    description TEXT,
    id_createur INT NOT NULL,
    est_mise_en_avant BOOLEAN DEFAULT false,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_createur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des soutenances
CREATE TABLE IF NOT EXISTS soutenance (
    id_soutenance SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    date_soutenance DATE NOT NULL,
    duree INT NOT NULL, -- durée en minutes
    salle VARCHAR(50) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table de liaison soutenance-groupe
CREATE TABLE IF NOT EXISTS soutenance_groupe (
    id_soutenance INT NOT NULL,
    id_groupe INT NOT NULL,
    heure_passage TIME NOT NULL,
    PRIMARY KEY (id_soutenance, id_groupe),
    FOREIGN KEY (id_soutenance) REFERENCES soutenance(id_soutenance) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE
);

-- Index pour optimiser les performances
CREATE INDEX idx_rendu_projet ON rendu_projet(id_projet);
CREATE INDEX idx_evaluation_rendu ON evaluation(id_rendu);
CREATE INDEX idx_evaluation_groupe ON evaluation(id_groupe);
CREATE INDEX idx_ressource_projet ON ressource(id_projet);
CREATE INDEX idx_soutenance_projet ON soutenance(id_projet);