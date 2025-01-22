-- Suppression des tables de liaison en premier
DROP TABLE IF EXISTS notification_utilisateur;
DROP TABLE IF EXISTS notification;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS livrable_groupe;
DROP TABLE IF EXISTS competence_etudiant;
DROP TABLE IF EXISTS soutenance_groupe;
DROP TABLE IF EXISTS groupe_etudiant;

-- Suppression des tables dépendantes
DROP TABLE IF EXISTS livrable;
DROP TABLE IF EXISTS competence;
DROP TABLE IF EXISTS soutenance;
DROP TABLE IF EXISTS ressource;
DROP TABLE IF EXISTS evaluation;
DROP TABLE IF EXISTS rendu_projet;
DROP TABLE IF EXISTS groupe_projet;
DROP TABLE IF EXISTS projet_responsable;

-- Maintenant on peut supprimer la table projet
DROP TABLE IF EXISTS projet;

-- Et enfin la table utilisateurs
DROP TABLE IF EXISTS utilisateurs;