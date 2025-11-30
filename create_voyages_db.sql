-- Script SQL pour créer la base de données et les tables nécessaires
-- Exécuter ce fichier dans phpMyAdmin (onglet SQL) ou via la CLI MySQL

-- IMPORTANT: ce script crée la base `voyages` si elle n'existe pas.
-- Il crée ensuite les tables dans l'ordre nécessaire et ajoute les clés étrangères.

CREATE DATABASE IF NOT EXISTS `voyages` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `voyages`;

-- -----------------------------------------------------
-- Table `visite`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `visite` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `ville` VARCHAR(50) NOT NULL,
  `pays` VARCHAR(50) NOT NULL,
  `datecreation` DATE DEFAULT NULL,
  `image_name` VARCHAR(255) DEFAULT NULL,
  `image_size` INT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT NULL,
  `note` INT DEFAULT NULL,
  `avis` LONGTEXT DEFAULT NULL,
  `tempmin` INT DEFAULT NULL,
  `tempmax` INT DEFAULT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `environnement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `environnement` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table de liaison many-to-many `visite_environnement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `visite_environnement` (
  `visite_id` INT NOT NULL,
  `environnement_id` INT NOT NULL,
  INDEX `IDX_VISITE` (`visite_id`),
  INDEX `IDX_ENV` (`environnement_id`),
  PRIMARY KEY (`visite_id`,`environnement_id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Ajout des contraintes de clé étrangère
ALTER TABLE `visite_environnement`
  ADD CONSTRAINT `FK_VISITE` FOREIGN KEY (`visite_id`) REFERENCES `visite` (`id`) ON DELETE CASCADE;

ALTER TABLE `visite_environnement`
  ADD CONSTRAINT `FK_ENV` FOREIGN KEY (`environnement_id`) REFERENCES `environnement` (`id`) ON DELETE CASCADE;

-- -----------------------------------------------------
-- Table `messenger_messages` (utilisée par Symfony Messenger)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` BIGINT AUTO_INCREMENT NOT NULL,
  `body` LONGTEXT NOT NULL,
  `headers` LONGTEXT NOT NULL,
  `queue_name` VARCHAR(190) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `available_at` DATETIME NOT NULL,
  `delivered_at` DATETIME DEFAULT NULL,
  INDEX `IDX_MESSENGER_QUEUE` (`queue_name`),
  INDEX `IDX_MESSENGER_AVAILABLE_AT` (`available_at`),
  INDEX `IDX_MESSENGER_DELIVERED_AT` (`delivered_at`),
  PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Fin du script
