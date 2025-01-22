/*
  # Création de la table ressource

  1. Nouvelle Table
    - `ressource`
      - `id_ressource` (uuid, clé primaire)
      - `titre` (text, non null)
      - `description` (text)
      - `type` (text, non null)
      - `lien` (text)
      - `fichier` (text)
      - `id_utilisateur` (uuid, clé étrangère)
      - `date_creation` (timestamptz)

  2. Relations
    - Clé étrangère vers la table utilisateurs
*/

CREATE TABLE IF NOT EXISTS ressource (
    id_ressource uuid PRIMARY KEY DEFAULT gen_random_uuid(),
    titre text NOT NULL,
    description text,
    type text NOT NULL,
    lien text,
    fichier text,
    id_utilisateur uuid REFERENCES utilisateurs(id),
    date_creation timestamptz DEFAULT CURRENT_TIMESTAMP
);

-- Enable RLS
ALTER TABLE ressource ENABLE ROW LEVEL SECURITY;

-- Create policies
CREATE POLICY "Tout le monde peut voir les ressources"
    ON ressource
    FOR SELECT
    TO authenticated
    USING (true);

CREATE POLICY "Les enseignants et admins peuvent ajouter des ressources"
    ON ressource
    FOR INSERT
    TO authenticated
    USING (
        EXISTS (
            SELECT 1 FROM utilisateurs
            WHERE id = auth.uid()
            AND role IN ('enseignant', 'admin')
        )
    );