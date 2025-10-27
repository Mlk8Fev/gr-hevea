# Commandes à exécuter sur le serveur

## 1. Se connecter au serveur SSH
```bash
ssh root@votre-ip-serveur
# OU
ssh votre-user@votre-ip-serveur
```

## 2. Aller dans le répertoire de l'application
```bash
cd /var/www/vhosts/votre-domaine.com/httpdocs
# OU selon votre configuration Hostinger
# cd /home/votre-user/httpdocs
```

## 3. Mettre à jour le code
```bash
git pull origin main
```

## 4. Exécuter le script de déploiement (si disponible)
```bash
chmod +x deploy-production.sh
./deploy-production.sh
```

## 5. OU manuellement :
```bash
# Résoudre le conflit (écraser les permissions locales)
git reset --hard origin/main

# Vérifier les migrations
php artisan migrate:status

# Vérifier que tout fonctionne
php artisan tinker
>>> \App\Models\User::count();
```

## 6. Vérifier les modifications
```bash
# Vérifier que l'image s'affiche dans les PDF
# Tester en générant un PDF de Self Declaration
```

## Résumé des modifications poussées :
✅ Correction affichage image background PDF (base64)
✅ Ajout COTRAF Korhogo dans CentreCollecteSeeder
✅ Ajout scripts d'optimisation serveur
✅ Correction affichage images PDF fiche enquête

