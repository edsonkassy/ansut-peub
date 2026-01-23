-- ============================================================================
-- TABLE: etablissements
-- Description: Table contenant la liste des établissements scolaires
-- Base de données: SQL Server
-- ============================================================================

-- Créer la table etablissements
CREATE TABLE [dbo].[etablissements] (
    [id] BIGINT PRIMARY KEY IDENTITY(1,1),
    [drena] NVARCHAR(255) NULL,
    [commune] NVARCHAR(255) NULL,
    [code_etab] NVARCHAR(255) NOT NULL UNIQUE,
    [etablissement] NVARCHAR(255) NOT NULL,
    [type_etab] NVARCHAR(255) NULL,
    [created_at] DATETIME DEFAULT GETDATE(),
    [updated_at] DATETIME DEFAULT GETDATE()
);

-- Créer les index
CREATE INDEX [idx_code_etab] ON [dbo].[etablissements]([code_etab]);
CREATE INDEX [idx_etablissement] ON [dbo].[etablissements]([etablissement]);
CREATE INDEX [idx_drena] ON [dbo].[etablissements]([drena]);
CREATE INDEX [idx_commune] ON [dbo].[etablissements]([commune]);

-- ============================================================================
-- EXEMPLES D'INSERTION DE DONNÉES
-- ============================================================================

-- Insérer des établissements d'exemple
INSERT INTO [dbo].[etablissements] ([drena], [commune], [code_etab], [etablissement], [type_etab]) VALUES
(N'DRENA Abidjan 1', N'Abidjan', N'ETAB001', N'Lycée Moderne d''Abidjan', N'public'),
(N'DRENA Abidjan 2', N'Abidjan', N'ETAB002', N'Lycée Classique d''Abidjan', N'public'),
(N'DRENA Abidjan 1', N'Abidjan', N'ETAB003', N'Lycée Privé Saint-Paul', N'prive_homologue'),
(N'DRENA Abidjan 2', N'Abidjan', N'ETAB004', N'Lycée Privé Sainte-Marie', N'prive_homologue'),
(N'DRENA Abidjan 1', N'Abidjan', N'ETAB005', N'Collège Moderne d''Abidjan', N'public'),
(N'DRENA Yamoussoukro', N'Yamoussoukro', N'ETAB006', N'Lycée Moderne de Yamoussoukro', N'public'),
(N'DRENA Yamoussoukro', N'Yamoussoukro', N'ETAB007', N'Lycée Classique de Yamoussoukro', N'public'),
(N'DRENA Bouaké', N'Bouaké', N'ETAB008', N'Lycée Moderne de Bouaké', N'public'),
(N'DRENA Bouaké', N'Bouaké', N'ETAB009', N'Lycée Privé de Bouaké', N'prive_homologue'),
(N'DRENA Daloa', N'Daloa', N'ETAB010', N'Lycée Moderne de Daloa', N'public');

-- ============================================================================
-- VÉRIFICATION
-- ============================================================================

-- Vérifier que la table a été créée
SELECT COUNT(*) as nombre_etablissements FROM [dbo].[etablissements];

-- Afficher tous les établissements
SELECT * FROM [dbo].[etablissements] ORDER BY [etablissement];

-- Afficher les établissements par type
SELECT [type_etab], COUNT(*) as nombre FROM [dbo].[etablissements] GROUP BY [type_etab];

-- Afficher les établissements par DRENA
SELECT [drena], COUNT(*) as nombre FROM [dbo].[etablissements] GROUP BY [drena] ORDER BY [drena];
