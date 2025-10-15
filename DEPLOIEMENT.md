# 🚀 GUIDE DE DÉPLOIEMENT - GR-HEVEA
## Hébergement sur Hostinger VPS (Ubuntu + Plesk)

---

## ✅ CHECKLIST COMPLÈTE D'HÉBERGEMENT

### 📋 PHASE 1 : PRÉPARATION (Sur votre Mac)

#### 1.1 Vérifications de sécurité
```bash
# Vérifier .env
cat .env | grep -E "APP_ENV|APP_DEBUG|APP_KEY"

# Vérifier .env.production
ls -la .env.production
```

#### 1.2 Nettoyage (DÉJÀ FAIT ✅)
- ✅ Routes de debug supprimées
- ✅ Fichiers temporaires (.bak, .backup, .DS_Store) supprimés
- ✅ Logs nettoyés

#### 1.3 Optimisation
```bash
# Installer dépendances production
composer install --optimize-autoloader --no-dev

# Créer caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 1.4 Créer archive de déploiement
```bash
tar -czf gr-hevea-deploy.tar.gz \
  --exclude=node_modules \
  --exclude=vendor \
  --exclude=.git \
  --exclude=storage/logs/*.log \
  .
```

---

### 📋 PHASE 2 : CONFIGURATION SERVEUR

#### 2.1 Connexion SSH
```bash
ssh root@VOTRE-IP-VPS
# Ou : ssh utilisateur@VOTRE-IP-VPS
```

#### 2.2 Vérifier prérequis
```bash
# PHP version
php -v  # Doit être 8.1+

# MySQL
mysql --version

# Composer
composer --version

# mysqldump
which mysqldump  # /usr/bin/mysqldump
```

#### 2.3 Installer PHP si nécessaire
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
  php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip \
  php8.2-gd php8.2-bcmath php8.2-intl
```

#### 2.4 Créer domaine dans Plesk
1. Connexion : `https://VOTRE-IP-VPS:8443`
2. Ajouter un domaine : `gr-hevea.fph-ci.com`
3. Document root : `/var/www/vhosts/gr-hevea.fph-ci.com/httpdocs`
4. PHP 8.2 en mode FastCGI

---

### 📋 PHASE 3 : DÉPLOIEMENT APPLICATION

#### 3.1 Transférer fichiers
```bash
# Depuis votre Mac
scp gr-hevea-deploy.tar.gz root@VOTRE-IP-VPS:/tmp/
```

#### 3.2 Installer sur serveur
```bash
# Sur le serveur
cd /var/www/vhosts/gr-hevea.fph-ci.com/httpdocs
rm -rf *
tar -xzf /tmp/gr-hevea-deploy.tar.gz -C .
composer install --optimize-autoloader --no-dev
```

#### 3.3 Configurer .env
```bash
cp .env.production .env
nano .env
```

**Valeurs à remplacer :**
```bash
APP_URL=https://gr-hevea.fph-ci.com

DB_HOST=localhost
DB_DATABASE=gr_hevea_prod
DB_USERNAME=gr_hevea_user
DB_PASSWORD=MOT_DE_PASSE_FORT

SESSION_SECURE_COOKIE=true
```

#### 3.4 Base de données

**Via Plesk :**
1. Bases de données > Ajouter
2. Nom : `gr_hevea_prod`
3. Utilisateur : `gr_hevea_user`
4. Mot de passe fort

**Ou via MySQL :**
```sql
CREATE DATABASE gr_hevea_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gr_hevea_user'@'localhost' IDENTIFIED BY 'MotDePasseFort123!';
GRANT ALL PRIVILEGES ON gr_hevea_prod.* TO 'gr_hevea_user'@'localhost';
FLUSH PRIVILEGES;
```

#### 3.5 Importer données
```bash
# Sur Mac : exporter
mysqldump -u root gr_hevea > gr_hevea_export.sql

# Transférer
scp gr_hevea_export.sql root@VOTRE-IP-VPS:/tmp/

# Sur serveur : importer
mysql -u gr_hevea_user -p gr_hevea_prod < /tmp/gr_hevea_export.sql
```

#### 3.6 Configuration Laravel
```bash
cd /var/www/vhosts/gr-hevea.fph-ci.com/httpdocs

# Générer clé
php artisan key:generate

# Lancer migrations (si nécessaire)
php artisan migrate --force

# Lien symbolique storage
php artisan storage:link
```

#### 3.7 Permissions
```bash
# Dossiers : 755
find . -type d -exec chmod 755 {} \;

# Fichiers : 644
find . -type f -exec chmod 644 {} \;

# Storage et cache : 775
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Sécuriser .env
chmod 600 .env
chown www-data:www-data .env
```

---

### 📋 PHASE 4 : CONFIGURATION WEB

#### 4.1 Document Root dans Plesk
1. Paramètres d'hébergement
2. Document root : `/var/www/vhosts/gr-hevea.fph-ci.com/httpdocs/public`
   **⚠️ IMPORTANT : Ajouter /public !**

#### 4.2 SSL/HTTPS (Let's Encrypt)
1. Plesk > SSL/TLS Certificates
2. Install free basic certificate
3. Cocher :
   - ✅ Sécuriser le domaine
   - ✅ Sécuriser www
   - ✅ Rediriger HTTP → HTTPS

---

### 📋 PHASE 5 : OPTIMISATIONS

#### 5.1 Caches production
```bash
# Vider anciens caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Créer caches optimisés
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5.2 Cron pour backups
```bash
crontab -e
```

Ajouter :
```bash
# Backup quotidien à 2h
0 2 * * * cd /var/www/vhosts/gr-hevea.fph-ci.com/httpdocs && php artisan backup:run >> /dev/null 2>&1

# Tâches planifiées
0 * * * * cd /var/www/vhosts/gr-hevea.fph-ci.com/httpdocs && php artisan schedule:run >> /dev/null 2>&1
```

#### 5.3 Tester backup
```bash
php artisan backup:run
ls -lh storage/app/private/Laravel/*.zip
```

---

### 📋 PHASE 6 : SÉCURITÉ

#### 6.1 Firewall (UFW)
```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw allow 8443/tcp  # Plesk
sudo ufw enable
```

#### 6.2 Bloquer .env
Ajouter dans `public/.htaccess` :
```apache
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

---

### 📋 PHASE 7 : TESTS

#### 7.1 Tester application
```
https://gr-hevea.fph-ci.com
```

✅ Login admin doit fonctionner
✅ Upload fichiers doit fonctionner
✅ 2FA doit fonctionner
✅ Emails doivent partir

#### 7.2 Vérifier headers sécurité
```bash
curl -I https://gr-hevea.fph-ci.com | grep -E "X-Frame|X-Content|X-XSS|Strict-Transport"
```

Doit afficher :
```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000
```

#### 7.3 Tester performances
```bash
curl -w "@-" -o /dev/null -s https://gr-hevea.fph-ci.com << 'EOF'
time_total:  %{time_total}\n
EOF
```

✅ Objectif : < 1 seconde

---

### 📋 PHASE 8 : MAINTENANCE

#### 8.1 Vider caches (si problème)
```bash
cd /var/www/vhosts/gr-hevea.fph-ci.com/httpdocs
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 8.2 Recréer caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 8.3 Redémarrer PHP
```bash
sudo systemctl restart php8.2-fpm
```

#### 8.4 Consulter logs
```bash
# Logs Laravel
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log

# Logs Plesk
# Via interface Plesk > Logs
```

---

### 📋 PHASE 9 : RESTAURATION (En cas de problème)

#### 9.1 Lister backups
```bash
php artisan backup:list
```

#### 9.2 Restaurer
```bash
# Extraire backup
unzip storage/app/private/Laravel/2025-XX-XX-02-00-01.zip -d /tmp/restore/

# Restaurer BDD
mysql -u gr_hevea_user -p gr_hevea_prod < /tmp/restore/db-dumps/mysql-gr_hevea.sql

# Restaurer fichiers
cp -r /tmp/restore/storage/app/public/* storage/app/public/
```

---

## ✅ CHECKLIST FINALE

### Sécurité
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] HTTPS activé
- [ ] SESSION_SECURE_COOKIE=true
- [ ] .env en chmod 600
- [ ] Routes debug supprimées ✅
- [ ] Firewall configuré
- [ ] Headers sécurité actifs
- [ ] Utilisateur MySQL dédié (pas root)

### Performances
- [ ] config:cache
- [ ] route:cache
- [ ] view:cache
- [ ] composer --no-dev

### Backups
- [ ] Cron configuré
- [ ] Test backup manuel
- [ ] Stockage distant (optionnel)

### Fonctionnel
- [ ] Login fonctionne
- [ ] Upload fonctionne
- [ ] 2FA fonctionne
- [ ] Emails fonctionnent

---

## 🆘 CONTACTS URGENCE

**Support Hostinger :**
- Email : support@hostinger.com
- Chat : https://www.hostinger.com/

**Accès Plesk :**
- URL : https://VOTRE-IP-VPS:8443
- User : admin ou root

---

## ⏱️ TEMPS ESTIMÉ

| Phase | Durée |
|-------|-------|
| Préparation | 15 min |
| Configuration serveur | 20 min |
| Déploiement | 10 min |
| BDD | 15 min |
| SSL | 5 min |
| Optimisations | 10 min |
| Tests | 10 min |
| **TOTAL** | **1h35** |

---

**🎉 Application prête pour la production !**

