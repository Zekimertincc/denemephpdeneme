ALTER TABLE utilisateur
    ADD COLUMN statut ENUM('ACTIF', 'EN_ATTENTE') NOT NULL DEFAULT 'ACTIF';

UPDATE utilisateur
SET statut = 'ACTIF'
WHERE statut IS NULL;
