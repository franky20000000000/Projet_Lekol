-- Crée la base et sélectionne le schéma
CREATE DATABASE IF NOT EXISTS `lekol` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `lekol`;

-- Table: parent
CREATE TABLE IF NOT EXISTS `parent` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `motDePasse` VARCHAR(255) NOT NULL,
  `telephone` VARCHAR(30) DEFAULT NULL,
  `ville` VARCHAR(100) DEFAULT NULL,
  `quartier` VARCHAR(100) DEFAULT NULL,
  `dateInscription` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_parent_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: repetiteur
CREATE TABLE IF NOT EXISTS `repetiteur` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `motDePasse` VARCHAR(255) NOT NULL,
  `telephone` VARCHAR(30) DEFAULT NULL,
  `ville` VARCHAR(100) DEFAULT NULL,
  `quartier` VARCHAR(100) DEFAULT NULL,
  `date_naissance` DATE DEFAULT NULL,
  `niveau_etudes` VARCHAR(100) DEFAULT NULL,
  `universite` VARCHAR(150) DEFAULT NULL,
  `filiere` VARCHAR(150) DEFAULT NULL,
  `matieres` TEXT DEFAULT NULL,
  `niveau_cible` VARCHAR(100) DEFAULT NULL,
  `zones` TEXT DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `piece_identite` VARCHAR(255) DEFAULT NULL,
  `certificat_scolarite` VARCHAR(255) DEFAULT NULL,
  `releve_bac` VARCHAR(255) DEFAULT NULL,
  `preuve_experience` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_repetiteur_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: avis
-- Le code filtre statut = 'approuve' et lit date_creation.
-- Par défaut on met 'approuve' pour que les nouveaux avis s’affichent immédiatement.
CREATE TABLE IF NOT EXISTS `avis` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` INT UNSIGNED NOT NULL,
  `repetiteur_id` INT UNSIGNED NOT NULL,
  `note` TINYINT UNSIGNED NOT NULL CHECK (`note` BETWEEN 1 AND 5),
  `commentaire` TEXT NOT NULL,
  `statut` ENUM('approuve','en_attente','rejete') NOT NULL DEFAULT 'approuve',
  `date_creation` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_avis_repetiteur` (`repetiteur_id`),
  KEY `idx_avis_parent` (`parent_id`),
  UNIQUE KEY `uniq_parent_repetiteur_once` (`parent_id`,`repetiteur_id`),
  CONSTRAINT `fk_avis_parent`
    FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_avis_repetiteur`
    FOREIGN KEY (`repetiteur_id`) REFERENCES `repetiteur` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


