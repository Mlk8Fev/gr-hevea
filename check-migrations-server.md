# Commandes pour vérifier les migrations sur le serveur

## 1. Vérifier l'état des migrations
```bash
php artisan migrate:status
```

## 2. Vérifier que toutes les migrations sont appliquées
```bash
php artisan migrate
```

## 3. Vérifier l'utilisateur AGC dans la base
```bash
php artisan tinker
>>> $user = \App\Models\User::where('role', 'agc')->first();
>>> echo $user->email . " - Role: " . $user->role;
```

## 4. Vérifier la structure de la table users
```sql
-- Dans phpMyAdmin
DESCRIBE users;
SHOW COLUMNS FROM users;
```

## 5. Si la colonne 'role' n'existe pas ou est différente
```sql
-- Vérifier que la colonne role existe
SELECT role FROM users WHERE email = 'votre-email-agc';
```

## 6. Vérifier que les middleware sont bien enregistrés
```bash
php artisan route:list | grep -i "cooperatives"
```

## 7. Vider tous les caches
```bash
php artisan optimize:clear
```

