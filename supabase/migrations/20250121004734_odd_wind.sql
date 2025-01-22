-- Modification de la table rendu_projet pour ajouter les colonnes manquantes
ALTER TABLE rendu_projet
ADD COLUMN note DECIMAL(4,2) DEFAULT NULL,
ADD COLUMN commentaire TEXT DEFAULT NULL;

-- Ajout des index pour optimiser les performances
CREATE INDEX idx_rendu_projet_date ON rendu_projet(date_creation);
CREATE INDEX idx_rendu_projet_note ON rendu_projet(note);