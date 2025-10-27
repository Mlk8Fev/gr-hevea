# Déploiement correctif AGC

## Problème
Les AGC n'ont pas accès aux pages coopératives et factures (erreur 403).

## Solution
La navigation AGC pointe maintenant vers les routes CS qui sont partagées avec les AGC.

## Commandes sur le serveur

```bash
# Aller dans le dossier
cd /var/www/vhosts/fphcigrainehevea.com/httpdocs

# Changer le PHP
export PATH=/opt/plesk/php/8.4/bin:$PATH

# Mettre à jour depuis Git
git pull origin main

# Vider TOUS les caches
php artisan optimize:clear

# NE PAS créer de cache route (laisser sans cache pour l'instant)
# Tester la connexion en AGC
```

## Vérification

Connectez-vous en AGC et testez :
- `/cs/cooperatives` - Devrait fonctionner ✅
- `/cs/factures` - Devrait fonctionner ✅

