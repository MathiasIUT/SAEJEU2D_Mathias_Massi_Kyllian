-- Table des utilisateurs (doit être créée en premier car référencée par d'autres tables)
CREATE TABLE utilisateurs (
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
CREATE TABLE projet (
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

-- Puis le reste des tables dans l'ordre...
-- (Continuez avec le reste des tables dans le même ordre que votre script,
-- en ajoutant ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci à chaque table)