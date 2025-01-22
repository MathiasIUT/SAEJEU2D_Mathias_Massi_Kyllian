/*
  # Schéma complet de la base de données
  
  Ce fichier contient toutes les tables nécessaires pour le projet de gestion des SAE,
  incluant les relations entre les tables et les données de test.

  1. Structure
    - Tables des utilisateurs et authentification
    - Tables des projets et responsables
    - Tables des groupes et étudiants
    - Tables des rendus et évaluations
    - Tables des compétences
    - Tables des livrables
    - Tables des ressources
    - Tables des soutenances
    - Tables des messages et notifications
    
  2. Données de test
    - Utilisateurs de test (admin, enseignants, étudiants)
    - Projets de test
    - Groupes et affectations
    - Rendus et évaluations
*/

-- Suppression des tables si elles existent
DROP TABLE IF EXISTS notification_utilisateur;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS livrable_groupe;
DROP TABLE IF EXISTS livrable;
DROP TABLE IF EXISTS competence_etudiant;
DROP TABLE IF EXISTS competence;
DROP TABLE IF EXISTS soutenance_groupe;
DROP TABLE IF EXISTS soutenance;
DROP TABLE IF EXISTS ressource;
DROP TABLE IF EXISTS evaluation;
DROP TABLE IF EXISTS rendu_projet;
DROP TABLE IF EXISTS groupe_etudiant;
DROP TABLE IF EXISTS groupe_projet;
DROP TABLE IF EXISTS projet_responsable;
DROP TABLE IF EXISTS projet;
DROP TABLE IF EXISTS utilisateurs;

-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id SERIAL PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant', 'etudiant') NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des projets
CREATE TABLE projet (
    id_projet SERIAL PRIMARY KEY,
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
);

-- Table des responsables de projet
CREATE TABLE projet_responsable (
    id_projet INT NOT NULL,
    id_enseignant INT NOT NULL,
    role ENUM('responsable', 'co-responsable', 'intervenant') NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_projet, id_enseignant),
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE,
    FOREIGN KEY (id_enseignant) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des groupes de projet
CREATE TABLE groupe_projet (
    id_groupe SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    titre_modifiable BOOLEAN DEFAULT false,
    image_modifiable BOOLEAN DEFAULT false,
    image VARCHAR(255),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table de liaison groupes-étudiants
CREATE TABLE groupe_etudiant (
    id_groupe INT NOT NULL,
    id_etudiant INT NOT NULL,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_groupe, id_etudiant),
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE,
    FOREIGN KEY (id_etudiant) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des rendus de projet
CREATE TABLE rendu_projet (
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
CREATE TABLE evaluation (
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
CREATE TABLE ressource (
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
CREATE TABLE soutenance (
    id_soutenance SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    date_soutenance DATE NOT NULL,
    duree INT NOT NULL,
    salle VARCHAR(50) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table de liaison soutenance-groupe
CREATE TABLE soutenance_groupe (
    id_soutenance INT NOT NULL,
    id_groupe INT NOT NULL,
    heure_passage TIME NOT NULL,
    PRIMARY KEY (id_soutenance, id_groupe),
    FOREIGN KEY (id_soutenance) REFERENCES soutenance(id_soutenance) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE
);

-- Table des compétences
CREATE TABLE competence (
    id_competence SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    code VARCHAR(50) NOT NULL,
    libelle VARCHAR(255) NOT NULL,
    description TEXT,
    niveau_requis INT NOT NULL CHECK (niveau_requis BETWEEN 1 AND 3),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table de liaison compétences-étudiants
CREATE TABLE competence_etudiant (
    id_competence INT NOT NULL,
    id_etudiant INT NOT NULL,
    niveau_acquis INT NOT NULL CHECK (niveau_acquis BETWEEN 1 AND 3),
    date_evaluation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    evaluateur_id INT NOT NULL,
    commentaire TEXT,
    PRIMARY KEY (id_competence, id_etudiant),
    FOREIGN KEY (id_competence) REFERENCES competence(id_competence) ON DELETE CASCADE,
    FOREIGN KEY (id_etudiant) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (evaluateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des livrables
CREATE TABLE livrable (
    id_livrable SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    type ENUM('document', 'code', 'presentation', 'autre') NOT NULL,
    date_limite TIMESTAMP NOT NULL,
    obligatoire BOOLEAN DEFAULT false,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table de liaison livrables-groupes
CREATE TABLE livrable_groupe (
    id_livrable INT NOT NULL,
    id_groupe INT NOT NULL,
    statut ENUM('non_rendu', 'en_cours', 'rendu', 'valide', 'refuse') NOT NULL DEFAULT 'non_rendu',
    date_rendu TIMESTAMP,
    commentaire TEXT,
    fichier VARCHAR(255),
    evaluateur_id INT,
    PRIMARY KEY (id_livrable, id_groupe),
    FOREIGN KEY (id_livrable) REFERENCES livrable(id_livrable) ON DELETE CASCADE,
    FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE,
    FOREIGN KEY (evaluateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL
);

-- Table des messages
CREATE TABLE message (
    id_message SERIAL PRIMARY KEY,
    id_expediteur INT NOT NULL,
    id_destinataire INT NOT NULL,
    sujet VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_envoi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lu BOOLEAN DEFAULT false,
    FOREIGN KEY (id_expediteur) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (id_destinataire) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des notifications
CREATE TABLE notification (
    id_notification SERIAL PRIMARY KEY,
    id_projet INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'warning', 'danger') NOT NULL DEFAULT 'info',
    destinataires VARCHAR(255) NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet) ON DELETE CASCADE
);

-- Table de liaison notifications-utilisateurs
CREATE TABLE notification_utilisateur (
    id_notification INT NOT NULL,
    id_utilisateur INT NOT NULL,
    lu BOOLEAN DEFAULT false,
    date_lecture TIMESTAMP,
    PRIMARY KEY (id_notification, id_utilisateur),
    FOREIGN KEY (id_notification) REFERENCES notification(id_notification) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Index pour optimiser les performances
CREATE INDEX idx_utilisateurs_role ON utilisateurs(role);
CREATE INDEX idx_projet_semestre ON projet(semestre);
CREATE INDEX idx_projet_dates ON projet(date_debut, date_fin);
CREATE INDEX idx_rendu_dates ON rendu_projet(date_limite);
CREATE INDEX idx_evaluation_dates ON evaluation(date_evaluation);
CREATE INDEX idx_ressource_projet ON ressource(id_projet);
CREATE INDEX idx_competence_projet ON competence(id_projet);
CREATE INDEX idx_livrable_projet ON livrable(id_projet);
CREATE INDEX idx_message_expediteur ON message(id_expediteur);
CREATE INDEX idx_message_destinataire ON message(id_destinataire);
CREATE INDEX idx_notification_projet ON notification(id_projet);

-- Insertion des données de test

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

-- Insertion des projets
INSERT INTO projet (titre, description, semestre, date_debut, date_fin, trello_link, git_link, id_responsable) VALUES
('Développement d''une application web', 'Création d''une application web de gestion de projets étudiants utilisant PHP et MySQL', 'S1', '2024-01-15', '2024-02-28', 'https://trello.com/projet1', 'https://github.com/projet1', 2),
('Base de données avancées', 'Conception et implémentation d''une base de données complexe avec triggers et procédures stockées', 'S2', '2024-03-01', '2024-04-15', 'https://trello.com/projet2', 'https://github.com/projet2', 2),
('Développement mobile', 'Création d''une application mobile Android avec Java', 'S3', '2024-02-01', '2024-03-30', 'https://trello.com/projet3', 'https://github.com/projet3', 3);

-- Insertion des responsables de projet
INSERT INTO projet_responsable (id_projet, id_enseignant, role) VALUES
(1, 2, 'responsable'),
(1, 3, 'co-responsable'),
(2, 2, 'responsable'),
(3, 3, 'responsable'),
(3, 2, 'intervenant');

-- Insertion des groupes
INSERT INTO groupe_projet (id_projet, titre) VALUES
(1, 'Groupe A1'),
(1, 'Groupe A2'),
(2, 'Groupe B1'),
(3, 'Groupe C1');

-- Association des étudiants aux groupes
INSERT INTO groupe_etudiant (id_groupe, id_etudiant) VALUES
(1, 4), -- etudiant1 dans Groupe A1
(1, 5), -- etudiant2 dans Groupe A1
(2, 6), -- etudiant3 dans Groupe A2
(3, 4), -- etudiant1 dans Groupe B1
(3, 5), -- etudiant2 dans Groupe B1
(4, 6); -- etudiant3 dans Groupe C1

-- Insertion des compétences
INSERT INTO competence (id_projet, code, libelle, description, niveau_requis) VALUES
(1, 'DEV1', 'Développement Web', 'Maîtrise des technologies web front-end et back-end', 2),
(1, 'BDD1', 'Base de données', 'Conception et manipulation de bases de données', 2),
(2, 'BDD2', 'Base de données avancées', 'Triggers, procédures stockées et optimisation', 3),
(3, 'MOB1', 'Développement mobile', 'Création d''applications Android natives', 2);

-- Insertion des livrables
INSERT INTO livrable (id_projet, titre, description, type, date_limite, obligatoire) VALUES
(1, 'Documentation technique', 'Documentation complète du projet', 'document', '2024-02-15 23:59:59', true),
(1, 'Code source', 'Code source commenté', 'code', '2024-02-20 23:59:59', true),
(2, 'Schéma de base de données', 'MCD et MPD', 'document', '2024-03-15 23:59:59', true),
(3, 'APK de l''application', 'Application compilée', 'autre', '2024-03-25 23:59:59', true);

-- Insertion des ressources
INSERT INTO ressource (id_projet, titre, type, url, description, id_createur) VALUES
(1, 'Tutoriel PHP', 'video', 'https://example.com/tuto-php', 'Introduction à PHP', 2),
(1, 'Documentation MySQL', 'pdf', 'https://example.com/doc-mysql', 'Manuel de référence MySQL', 2),
(2, 'Cours sur les triggers', 'pdf', 'https://example.com/triggers', 'Conception de triggers', 3),
(3, 'Guide Android', 'lien', 'https://developer.android.com', 'Documentation officielle Android', 3);

-- Insertion des soutenances
INSERT INTO soutenance (id_projet, date_soutenance, duree, salle) VALUES
(1, '2024-02-28', 20, 'A101'),
(2, '2024-04-15', 30, 'B202'),
(3, '2024-03-30', 25, 'C303');

-- Insertion des messages
INSERT INTO message (id_expediteur, id_destinataire, sujet, contenu) VALUES
(2, 4, 'Retour sur le projet', 'Voici mes commentaires sur votre dernière version...'),
(3, 5, 'Question sur le rendu', 'Pouvez-vous préciser le format attendu ?'),
(4, 2, 'Demande de rendez-vous', 'Serait-il possible de vous rencontrer pour discuter du projet ?');

-- Insertion des notifications
INSERT INTO notification (id_projet, titre, message, type, destinataires) VALUES
(1, 'Rappel deadline', 'N''oubliez pas le rendu final pour la semaine prochaine', 'warning', 'all'),
(2, 'Nouvelle ressource', 'Un nouveau document a été ajouté dans les ressources', 'info', 'students'),
(3, 'Changement de salle', 'La soutenance aura lieu en salle C304', 'info', 'all');