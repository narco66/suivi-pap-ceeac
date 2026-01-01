-- Script SQL pour créer la table ressources manuellement
-- Exécutez ce script dans votre base de données MySQL si la migration ne fonctionne pas

CREATE TABLE IF NOT EXISTS `ressources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `type` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL DEFAULT 'general',
  `version` varchar(255) NOT NULL DEFAULT '1.0',
  `fichier` varchar(255) DEFAULT NULL,
  `nom_fichier_original` varchar(255) DEFAULT NULL,
  `taille_fichier` int(11) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `est_public` tinyint(1) NOT NULL DEFAULT 1,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `nombre_telechargements` int(11) NOT NULL DEFAULT 0,
  `cree_par_id` bigint(20) unsigned DEFAULT NULL,
  `date_publication` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ressources_cree_par_id_foreign` (`cree_par_id`),
  KEY `ressources_type_categorie_est_actif_index` (`type`,`categorie`,`est_actif`),
  KEY `ressources_est_public_index` (`est_public`),
  CONSTRAINT `ressources_cree_par_id_foreign` FOREIGN KEY (`cree_par_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



