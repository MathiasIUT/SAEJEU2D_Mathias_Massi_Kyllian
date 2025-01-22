/*
  # Ajout des tables pour les compétences et les livrables

  1. Nouvelles Tables
    - `competence`
      - `id_competence` (serial, primary key)
      - `id_projet` (int, foreign key)
      - `code` (varchar)
      - `libelle` (varchar)
      - `description` (text)
      - `niveau_requis` (int)
    - `competence_etudiant`
      - `id_competence` (int, foreign key)
      - `id_etudiant` (int, foreign key)
      - `niveau_acquis` (int)
      - `date_evaluation` (timestamp)
    - `livrable`
      - `id_livrable` (serial, primary key)
      - `id_projet` (int, foreign key)
      - `titre` (varchar)
      - `description` (text)
      - `type` (enum)
      - `date_limite` (timestamp)
      - `obligatoire` (boolean)
    - `livrable_groupe`
      - `id_livrable` (int, foreign key)
      - `id_groupe` (int, foreign key)
      - `statut` (enum)
      - `date_rendu` (timestamp)
      - `commentaire` (text)

  2. Security
    - Enable RLS on all tables
    - Add policies for authenticated users
*/

-- Table des compétences
CREATE TABLE IF NOT EXISTS competence (
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
CREATE TABLE IF NOT EXISTS competence_etudiant (
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
CREATE TABLE IF NOT EXISTS livrable (
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
CREATE TABLE IF NOT EXISTS livrable_groupe (
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

-- Index pour optimiser les performances
CREATE INDEX idx_competence_projet ON competence(id_projet);
CREATE INDEX idx_competence_code ON competence(code);
CREATE INDEX idx_competence_etudiant_niveau ON competence_etudiant(niveau_acquis);
CREATE INDEX idx_livrable_projet ON livrable(id_projet);
CREATE INDEX idx_livrable_date_limite ON livrable(date_limite);
CREATE INDEX idx_livrable_groupe_statut ON livrable_groupe(statut);

-- Enable Row Level Security
ALTER TABLE competence ENABLE ROW LEVEL SECURITY;
ALTER TABLE competence_etudiant ENABLE ROW LEVEL SECURITY;
ALTER TABLE livrable ENABLE ROW LEVEL SECURITY;
ALTER TABLE livrable_groupe ENABLE ROW LEVEL SECURITY;

-- Policies for competence table
CREATE POLICY "Users can read project competences"
    ON competence
    FOR SELECT
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM projet p
        WHERE p.id_projet = competence.id_projet
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
            OR EXISTS (
                SELECT 1 FROM groupe_projet gp
                JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
                WHERE gp.id_projet = p.id_projet
                AND ge.id_etudiant = auth.uid()
            )
        )
    ));

CREATE POLICY "Project responsibles can manage competences"
    ON competence
    FOR ALL
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM projet p
        WHERE p.id_projet = competence.id_projet
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
        )
    ));

-- Policies for competence_etudiant table
CREATE POLICY "Teachers can manage competence evaluations"
    ON competence_etudiant
    FOR ALL
    TO authenticated
    USING (
        auth.uid() IN (
            SELECT id FROM utilisateurs WHERE role IN ('admin', 'enseignant')
        )
    );

CREATE POLICY "Students can view their own competence evaluations"
    ON competence_etudiant
    FOR SELECT
    TO authenticated
    USING (auth.uid() = id_etudiant);

-- Policies for livrable table
CREATE POLICY "Users can read project livrables"
    ON livrable
    FOR SELECT
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM projet p
        WHERE p.id_projet = livrable.id_projet
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
            OR EXISTS (
                SELECT 1 FROM groupe_projet gp
                JOIN groupe_etudiant ge ON gp.id_groupe = ge.id_groupe
                WHERE gp.id_projet = p.id_projet
                AND ge.id_etudiant = auth.uid()
            )
        )
    ));

CREATE POLICY "Project responsibles can manage livrables"
    ON livrable
    FOR ALL
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM projet p
        WHERE p.id_projet = livrable.id_projet
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
        )
    ));

-- Policies for livrable_groupe table
CREATE POLICY "Users can read their group livrables"
    ON livrable_groupe
    FOR SELECT
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM groupe_projet gp
        WHERE gp.id_groupe = livrable_groupe.id_groupe
        AND (
            EXISTS (
                SELECT 1 FROM projet p
                WHERE p.id_projet = gp.id_projet
                AND (
                    p.id_responsable = auth.uid()
                    OR EXISTS (
                        SELECT 1 FROM projet_responsable pr
                        WHERE pr.id_projet = p.id_projet
                        AND pr.id_enseignant = auth.uid()
                    )
                )
            )
            OR EXISTS (
                SELECT 1 FROM groupe_etudiant ge
                WHERE ge.id_groupe = gp.id_groupe
                AND ge.id_etudiant = auth.uid()
            )
        )
    ));

CREATE POLICY "Students can submit livrables"
    ON livrable_groupe
    FOR UPDATE
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM groupe_etudiant ge
        WHERE ge.id_groupe = livrable_groupe.id_groupe
        AND ge.id_etudiant = auth.uid()
    ));

CREATE POLICY "Teachers can evaluate livrables"
    ON livrable_groupe
    FOR UPDATE
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM groupe_projet gp
        JOIN projet p ON gp.id_projet = p.id_projet
        WHERE gp.id_groupe = livrable_groupe.id_groupe
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
        )
    ));