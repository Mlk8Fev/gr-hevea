# Commandes à exécuter sur le serveur pour corriger les erreurs

## 1. Problème : Colonne `secteur_id` manquante dans `connaissements`

```bash
# Se connecter au serveur
ssh root@votre-serveur

# Aller dans le dossier de l'app
cd /var/www/vhosts/fphcigrainehevea.com/httpdocs

# Exécuter la migration pour ajouter secteur_id
php artisan migrate
```

**OU manuellement :**

```sql
-- Exécuter dans phpMyAdmin ou MySQL
ALTER TABLE connaissements ADD COLUMN secteur_id BIGINT UNSIGNED NULL AFTER cooperative_id;
ALTER TABLE connaissements ADD CONSTRAINT connaissements_secteur_id_foreign FOREIGN KEY (secteur_id) REFERENCES secteurs(id) ON DELETE SET NULL;
```

## 2. Problème : `object_type` ne peut pas être NULL

```sql
-- Modifier la colonne object_type pour permettre NULL
ALTER TABLE audit_logs MODIFY COLUMN object_type VARCHAR(255) NULL;
ALTER TABLE audit_logs MODIFY COLUMN object_id BIGINT UNSIGNED NULL;
```

## 3. Après les corrections

```bash
# Vider les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

Ensuite, tester la connexion AGC.

