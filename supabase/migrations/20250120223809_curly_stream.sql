/*
  # Ajout des tables pour la messagerie et les notifications

  1. Nouvelles Tables
    - `message`
      - `id_message` (serial, primary key)
      - `id_expediteur` (int, foreign key)
      - `id_destinataire` (int, foreign key)
      - `sujet` (varchar)
      - `contenu` (text)
      - `date_envoi` (timestamp)
      - `lu` (boolean)
    - `notification`
      - `id_notification` (serial, primary key)
      - `id_projet` (int, foreign key)
      - `titre` (varchar)
      - `message` (text)
      - `type` (enum)
      - `destinataires` (varchar)
      - `date_creation` (timestamp)
    - `notification_utilisateur`
      - `id_notification` (int, foreign key)
      - `id_utilisateur` (int, foreign key)
      - `lu` (boolean)
      - `date_lecture` (timestamp)

  2. Security
    - Enable RLS on all tables
    - Add policies for authenticated users
*/

-- Table des messages
CREATE TABLE IF NOT EXISTS message (
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
CREATE TABLE IF NOT EXISTS notification (
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
CREATE TABLE IF NOT EXISTS notification_utilisateur (
    id_notification INT NOT NULL,
    id_utilisateur INT NOT NULL,
    lu BOOLEAN DEFAULT false,
    date_lecture TIMESTAMP,
    PRIMARY KEY (id_notification, id_utilisateur),
    FOREIGN KEY (id_notification) REFERENCES notification(id_notification) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Index pour optimiser les performances
CREATE INDEX idx_message_expediteur ON message(id_expediteur);
CREATE INDEX idx_message_destinataire ON message(id_destinataire);
CREATE INDEX idx_message_date ON message(date_envoi);
CREATE INDEX idx_notification_projet ON notification(id_projet);
CREATE INDEX idx_notification_date ON notification(date_creation);
CREATE INDEX idx_notification_utilisateur_lu ON notification_utilisateur(lu);

-- Enable Row Level Security
ALTER TABLE message ENABLE ROW LEVEL SECURITY;
ALTER TABLE notification ENABLE ROW LEVEL SECURITY;
ALTER TABLE notification_utilisateur ENABLE ROW LEVEL SECURITY;

-- Policies for message table
CREATE POLICY "Users can read their own messages"
    ON message
    FOR SELECT
    TO authenticated
    USING (auth.uid() = id_expediteur OR auth.uid() = id_destinataire);

CREATE POLICY "Users can send messages"
    ON message
    FOR INSERT
    TO authenticated
    WITH CHECK (auth.uid() = id_expediteur);

CREATE POLICY "Users can delete their own messages"
    ON message
    FOR DELETE
    TO authenticated
    USING (auth.uid() = id_expediteur OR auth.uid() = id_destinataire);

-- Policies for notification table
CREATE POLICY "Users can read project notifications"
    ON notification
    FOR SELECT
    TO authenticated
    USING (EXISTS (
        SELECT 1 FROM projet p
        WHERE p.id_projet = notification.id_projet
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
        )
    ));

CREATE POLICY "Project responsibles can create notifications"
    ON notification
    FOR INSERT
    TO authenticated
    WITH CHECK (EXISTS (
        SELECT 1 FROM projet p
        WHERE p.id_projet = notification.id_projet
        AND (
            p.id_responsable = auth.uid()
            OR EXISTS (
                SELECT 1 FROM projet_responsable pr
                WHERE pr.id_projet = p.id_projet
                AND pr.id_enseignant = auth.uid()
            )
        )
    ));

-- Policies for notification_utilisateur table
CREATE POLICY "Users can read their own notification status"
    ON notification_utilisateur
    FOR SELECT
    TO authenticated
    USING (auth.uid() = id_utilisateur);

CREATE POLICY "Users can update their own notification status"
    ON notification_utilisateur
    FOR UPDATE
    TO authenticated
    USING (auth.uid() = id_utilisateur);