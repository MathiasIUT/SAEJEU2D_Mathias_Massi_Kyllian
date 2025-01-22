-- Modification de la table rendu_projet pour ajouter les colonnes manquantes
ALTER TABLE rendu_projet
ADD COLUMN id_groupe INT NOT NULL AFTER id_projet,
ADD FOREIGN KEY (id_groupe) REFERENCES groupe_projet(id_groupe) ON DELETE CASCADE;

-- Ajout des index pour optimiser les performances
CREATE INDEX idx_rendu_projet_groupe ON rendu_projet(id_groupe);