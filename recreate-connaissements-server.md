# Commandes pour recréer la table connaissements sur le serveur

## Sur le serveur :

```bash
# Se connecter
ssh root@votre-serveur

# Aller dans le dossier de l'app
cd /var/www/vhosts/fphcigrainehevea.com/httpdocs

# Exécuter la migration pour créer la table connaissements
php artisan migrate --path=database/migrations/2025_07_28_122342_create_connaissements_table.php

# Ajouter la colonne secteur_id
php artisan migrate --path=database/migrations/2025_07_28_152909_add_programmation_validation_to_connaissements_table.php

# Supprimer la colonne numero (si elle existe)
php artisan migrate --path=database/migrations/2025_09_03_083753_remove_numero_column_from_connaissements_table.php
```

## OU via phpMyAdmin (plus rapide) :

```sql
-- Recréer la table connaissements
CREATE TABLE `connaissements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_livraison` varchar(255) DEFAULT NULL,
  `statut` enum('programme','valide') NOT NULL DEFAULT 'programme',
  `cooperative_id` bigint unsigned NOT NULL,
  `secteur_id` bigint unsigned DEFAULT NULL,
  `centre_collecte_id` bigint unsigned NOT NULL,
  `lieu_depart` varchar(255) NOT NULL,
  `sous_prefecture` varchar(255) NOT NULL,
  `transporteur_nom` varchar(255) NOT NULL,
  `transporteur_immatriculation` varchar(255) NOT NULL,
  `chauffeur_nom` varchar(255) NOT NULL,
  `destinataire_type` enum('entrepot','cooperative','acheteur') NOT NULL,
  `destinataire_id` bigint unsigned DEFAULT NULL,
  `nombre_sacs` int NOT NULL,
  `poids_brut_estime` decimal(10,2) NOT NULL,
  `poids_net` decimal(10,2) DEFAULT NULL,
  `signature_cooperative` varchar(255) DEFAULT NULL,
  `signature_fphci` varchar(255) DEFAULT NULL,
  `date_validation` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `validated_by` bigint unsigned DEFAULT NULL,
  `programmed_by` bigint unsigned DEFAULT NULL,
  `date_programmation` timestamp NULL DEFAULT NULL,
  `date_reception` date DEFAULT NULL,
  `heure_arrivee` timestamp NULL DEFAULT NULL,
  `poids_net_reel` decimal(10,2) DEFAULT NULL,
  `date_validation_reelle` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `connaissements_cooperative_id_foreign` (`cooperative_id`),
  KEY `connaissements_centre_collecte_id_foreign` (`centre_collecte_id`),
  KEY `connaissements_secteur_id_foreign` (`secteur_id`),
  KEY `connaissements_created_by_foreign` (`created_by`),
  KEY `connaissements_validated_by_foreign` (`validated_by`),
  KEY `connaissements_programmed_by_foreign` (`programmed_by`),
  CONSTRAINT `connaissements_centre_collecte_id_foreign` FOREIGN KEY (`centre_collecte_id`) REFERENCES `centres_collecte` (`id`) ON DELETE CASCADE,
  CONSTRAINT `connaissements_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE CASCADE,
  CONSTRAINT `connaissements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `connaissements_programmed_by_foreign` FOREIGN KEY (`programmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `connaissements_secteur_id_foreign` FOREIGN KEY (`secteur_id`) REFERENCES `secteurs` (`id`) ON DELETE SET NULL,
  CONSTRAINT `connaissements_validated_by_foreign` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Ensuite, videz les caches :

```bash
php artisan cache:clear
php artisan config:clear
```

