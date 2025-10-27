# Tester les corrections sur le serveur

## 1. Se connecter en tant qu'AGC
- Vérifier que vous pouvez accéder au dashboard
- Vérifier que les statistiques s'affichent

## 2. Vérifier la base de données

```sql
-- Vérifier que secteur_id existe
DESCRIBE connaissements;

-- Vérifier que object_type peut être NULL
DESCRIBE audit_logs;
```

## 3. Vider les caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 4. Tester la connexion AGC

Si vous avez toujours l'erreur "Unknown column 'secteur_id'", cela signifie que :
- Les colonnes n'ont pas été ajoutées correctement
- Il faut vérifier dans phpMyAdmin

