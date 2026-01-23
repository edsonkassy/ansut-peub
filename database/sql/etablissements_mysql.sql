-- ============================================================================
-- TABLE: etablissements
-- Description: Table contenant la liste des établissements scolaires
-- Base de données: MySQL
-- ============================================================================

-- Créer la table etablissements
CREATE TABLE IF NOT EXISTS `etablissements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `drena` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Direction Régionale de l''Éducation Nationale',
  `commune` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Commune',
  `code_etab` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL UNIQUE COMMENT 'Code établissement',
  `etablissement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom de l''établissement',
  `type_etab` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type d''établissement (Lycée, Collège, etc.)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date de dernière modification',
  KEY `idx_code_etab` (`code_etab`),
  KEY `idx_etablissement` (`etablissement`),
  KEY `idx_drena` (`drena`),
  KEY `idx_commune` (`commune`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Liste des établissements scolaires';

-- ============================================================================
-- EXEMPLES D'INSERTION DE DONNÉES
-- ============================================================================

-- Insérer des établissements d'exemple
INSERT INTO `etablissements` (`drena`, `commune`, `code_etab`, `etablissement`, `type_etab`) VALUES
('DRENA Abidjan 1', 'Abidjan', 'ETAB001', 'Lycée Moderne d''Abidjan', 'public'),
('DRENA Abidjan 2', 'Abidjan', 'ETAB002', 'Lycée Classique d''Abidjan', 'public'),
('DRENA Abidjan 1', 'Abidjan', 'ETAB003', 'Lycée Privé Saint-Paul', 'prive_homologue'),
('DRENA Abidjan 2', 'Abidjan', 'ETAB004', 'Lycée Privé Sainte-Marie', 'prive_homologue'),
('DRENA Abidjan 1', 'Abidjan', 'ETAB005', 'Collège Moderne d''Abidjan', 'public'),
('DRENA Yamoussoukro', 'Yamoussoukro', 'ETAB006', 'Lycée Moderne de Yamoussoukro', 'public'),
('DRENA Yamoussoukro', 'Yamoussoukro', 'ETAB007', 'Lycée Classique de Yamoussoukro', 'public'),
('DRENA Bouaké', 'Bouaké', 'ETAB008', 'Lycée Moderne de Bouaké', 'public'),
('DRENA Bouaké', 'Bouaké', 'ETAB009', 'Lycée Privé de Bouaké', 'prive_homologue'),
('DRENA Daloa', 'Daloa', 'ETAB010', 'Lycée Moderne de Daloa', 'public');

-- ============================================================================
-- VÉRIFICATION
-- ============================================================================

-- Vérifier que la table a été créée
SELECT COUNT(*) as nombre_etablissements FROM `etablissements`;

-- Afficher tous les établissements
SELECT * FROM `etablissements` ORDER BY `etablissement`;

-- Afficher les établissements par type
SELECT `type_etab`, COUNT(*) as nombre FROM `etablissements` GROUP BY `type_etab`;

-- Afficher les établissements par DRENA
SELECT `drena`, COUNT(*) as nombre FROM `etablissements` GROUP BY `drena` ORDER BY `drena`;
