/*
# Création de la table ressource

1. Nouvelle Table
  - `ressource`
    - `id_ressource` (varchar(36), clé primaire)
    - `titre` (varchar(255), non null)
    - `description` (text)
    - `type` (varchar(50), non null)
    - `lien` (text)
    - `fichier` (varchar(255))
    - `id_utilisateur` (varchar(36), clé étrangère)
    - `date_creation` (datetime)

2. Relations
  - Clé étrangère vers la table utilisateurs
*/

CREATE TABLE IF NOT EXISTS ressource (
    id_ressource VARCHAR(36) PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    type VARCHAR(50) NOT NULL,
    lien TEXT,
    fichier VARCHAR(255),
    id_utilisateur VARCHAR(36),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ajout d'un trigger pour générer un UUID pour id_ressource
DELIMITER //
CREATE TRIGGER before_insert_ressource
BEFORE INSERT ON ressource
FOR EACH ROW
BEGIN
    IF NEW.id_ressource IS NULL THEN
        SET NEW.id_ressource = UUID();
    END IF;
END//
DELIMITER ;