-- Création de la base de données
CREATE DATABASE IF NOT EXISTS dutinfopw201613bis;
USE dutinfopw201613bis;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant', 'etudiant') NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des projets
CREATE TABLE IF NOT EXISTS projet (
    id_projet INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    semestre ENUM('S1', 'S2', 'S3', 'S4') NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    trello_link VARCHAR(255),
    git_link VARCHAR(255),
    id_responsable INT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_responsable) REFERENCES utilisateurs(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des rendus
CREATE TABLE IF NOT EXISTS rendu (
    id_rendu INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    id_etudiant INT NOT NULL,
    fichier VARCHAR(255) NOT NULL,
    commentaire TEXT,
    note DECIMAL(4,2),
    date_rendu TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_etudiant) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table des groupes
CREATE TABLE IF NOT EXISTS groupe (
    id_groupe INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    annee_scolaire VARCHAR(9) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table de liaison étudiants-groupes
CREATE TABLE IF NOT EXISTS etudiant_groupe (
    id_etudiant INT NOT NULL,
    id_groupe INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_etudiant, id_groupe),
    FOREIGN KEY (id_etudiant) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe(id_groupe) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertions des données de test

-- Insertion des utilisateurs
-- Note: Les mots de passe sont hashés avec password_hash() en PHP
-- Ici, tous les mots de passe sont 'password123'
INSERT INTO utilisateurs (login, password, role, nom, prenom, email) VALUES
('admin', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'admin', 'Admin', 'System', 'admin@iut.fr'),
('prof1', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'enseignant', 'Dupont', 'Jean', 'jean.dupont@iut.fr'),
('prof2', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'enseignant', 'Martin', 'Marie', 'marie.martin@iut.fr'),
('etudiant1', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'etudiant', 'Dubois', 'Pierre', 'pierre.dubois@etu.iut.fr'),
('etudiant2', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'etudiant', 'Leroy', 'Sophie', 'sophie.leroy@etu.iut.fr'),
('etudiant3', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'etudiant', 'Moreau', 'Lucas', 'lucas.moreau@etu.iut.fr');

-- Insertion des groupes
INSERT INTO groupe (nom, annee_scolaire) VALUES
('BUT1-A', '2023-2024'),
('BUT1-B', '2023-2024'),
('BUT2-A', '2023-2024');

-- Association des étudiants aux groupes
INSERT INTO etudiant_groupe (id_etudiant, id_groupe) VALUES
(4, 1), -- etudiant1 dans BUT1-A
(5, 1), -- etudiant2 dans BUT1-A
(6, 2); -- etudiant3 dans BUT1-B

-- Insertion des projets
INSERT INTO projet (titre, description, semestre, date_debut, date_fin, trello_link, git_link, id_responsable) VALUES
('Développement d''une application web', 'Création d''une application web de gestion de projets étudiants utilisant PHP et MySQL', 'S1', '2024-01-15', '2024-02-28', 'https://trello.com/projet1', 'https://github.com/projet1', 2),
('Base de données avancées', 'Conception et implémentation d''une base de données complexe avec triggers et procédures stockées', 'S2', '2024-03-01', '2024-04-15', 'https://trello.com/projet2', 'https://github.com/projet2', 2),
('Développement mobile', 'Création d''une application mobile Android avec Java', 'S3', '2024-02-01', '2024-03-30', 'https://trello.com/projet3', 'https://github.com/projet3', 3);

-- Insertion des rendus
INSERT INTO rendu (id_projet, id_etudiant, fichier, commentaire, note, date_rendu) VALUES
(1, 4, 'projet1_etudiant1.zip', 'Bon travail, interface bien pensée', 16.50, '2024-02-15 14:30:00'),
(1, 5, 'projet1_etudiant2.zip', 'Quelques bugs à corriger', 14.00, '2024-02-14 16:45:00'),
(2, 6, 'projet2_etudiant3.zip', 'Excellent travail sur les triggers', 18.00, '2024-03-20 09:15:00');

-- Création des index pour optimiser les performances
CREATE INDEX idx_utilisateurs_role ON utilisateurs(role);
CREATE INDEX idx_projet_semestre ON projet(semestre);
CREATE INDEX idx_projet_dates ON projet(date_debut, date_fin);
CREATE INDEX idx_rendu_dates ON rendu(date_rendu);
CREATE INDEX idx_groupe_annee ON groupe(annee_scolaire);