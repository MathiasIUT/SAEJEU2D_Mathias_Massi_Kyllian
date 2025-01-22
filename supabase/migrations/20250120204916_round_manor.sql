-- Création de la base de données
CREATE DATABASE IF NOT EXISTS gestion_sae;
USE gestion_sae;

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'enseignant', 'etudiant') NOT NULL
);

-- Table projet
CREATE TABLE IF NOT EXISTS projet (
    id_projet INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    semestre VARCHAR(255) NOT NULL,
    trello_link VARCHAR(255),
    git_link VARCHAR(255),
    id_responsable INT NOT NULL,
    FOREIGN KEY (id_responsable) REFERENCES utilisateurs(id)
);

-- Table rendu
CREATE TABLE IF NOT EXISTS rendu (
    id_rendu INT AUTO_INCREMENT PRIMARY KEY,
    id_projet INT NOT NULL,
    id_etudiant INT NOT NULL,
    fichier VARCHAR(255) NOT NULL,
    date_rendu DATETIME NOT NULL,
    FOREIGN KEY (id_projet) REFERENCES projet(id_projet),
    FOREIGN KEY (id_etudiant) REFERENCES utilisateurs(id)
);

-- Insertion d'un utilisateur admin par défaut (mot de passe: admin123)
INSERT INTO utilisateurs (login, password, role) VALUES 
('admin', '$2y$10$8I6/0U6pXXnyKwh9VLxz6ODqQZxHxkzQh0M7NpI.fX5GpZO4Y1nye', 'admin');