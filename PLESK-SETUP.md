# Guide de Configuration GR-HEVEA sur Plesk (Hostinger)

## 📋 Prérequis
- VPS Hostinger avec Plesk
- 32GB RAM
- PHP 8.3+
- MySQL/MariaDB
- Accès SSH activé

---

## 🔧 ÉTAPE 1 : Configuration PHP via Plesk

### 1.1. Aller dans Plesk
```
Plesk → Domaines → [votre-domaine.com] → PHP Settings
```

### 1.2. Configurations à appliquer

**Version PHP :** 8.3

**Paramètres personnalisés (php.ini) :**
```ini
# Mémoire et performance
memory_limit = 512M
max_execution_time = 180
max_input_time = 180
upload_max_filesize = 100M
post_max_size = 100M

# OPcache (Performance)
opcache.enable=1
opcache.memory_consumption=256M
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
opcache.max_wasted_percentage=5
opcache.validate_timestamps=0

# Realpath cache
realpath_cache_size = 10M
realpath_cache_ttl = 3600
```

### 1.3. Gestionnaire PHP-FPM
```
Plesk → Tools & Settings → PHP-FPM Settings
```

Modifier :
- **Process manager** : dynamic
- **Max children** : 200
- **Start servers** : 50
- **Min spare servers** : 30
- **Max spare servers** : 80
- **Max requests** : 1000

---

## 🗄️ ÉTAPE 2 : Configuration MySQL/MariaDB

### 2.1. Accès via SSH
```bash
ssh root@votre-ip-serveur

# Éditer le fichier de configuration MySQL
nano /etc/mysql/mariadb.conf.d/50-server.cnf
```

### 2.2. Ajouter à la fin du fichier
```ini
[mysqld]
# Buffer Pool - 50% de la RAM (16GB sur 32GB)
innodb_buffer_pool_size = 16G
innodb_buffer_pool_instances = 8

# Logs
innodb_log_file_size = 512M
innodb_log_buffer_size = 64M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT

# Performance
innodb_file_per_table = 1
innodb_read_io_threads = 8
innodb_write_io_threads = 8
innodb_thread_concurrency = 0

# Connexions
max_connections = 300
max_connect_errors = 100000
thread_cache_size = 16
table_open_cache = 4000
table_definition_cache = 2000

# Query Cache
query_cache_type = 1
query_cache_size = 256M
query_cache_limit = 2M

# Temporaires
tmp_table_size = 256M
max_heap_table_size = 256M
sort_buffer_size = 2M
join_buffer_size = 2M

# Réseau
max_allowed_packet = 64M
interactive_timeout = 300
wait_timeout = 300
```

### 2.3. Redémarrer MySQL
```bash
systemctl restart mysql
```

---

## 🌐 ÉTAPE 3 : Configuration Nginx (si utilisé)

### 3.1. Via SSH
```bash
nano /etc/nginx/nginx.conf
```

### 3.2. Configuration optimale
```nginx
user www-data;
worker_processes auto;
worker_rlimit_nofile 65535;

events {
    worker_connections 4096;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    keepalive_requests 100;
    
    client_max_body_size 100M;
    client_body_buffer_size 128k;
    
    gzip on;
    gzip_vary on;
    gzip_min_length 1000;
    gzip_comp_level 6;
    gzip_types text/plain text/css application/json application/javascript text/xml;
    
    include /etc/nginx/conf.d/*.conf;
    include /etc/nginx/sites-enabled/*;
}
```

### 3.3. Tester et redémarrer
```bash
nginx -t
systemctl restart nginx
```

---

## 📦 ÉTAPE 4 : Déploiement de l'Application

### 4.1. Via Git (Recommandé)

**Sur votre ordinateur local :**
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/gr-hevea
git add .
git commit -m "Configuration pour production"
git push origin main
```

**Sur le serveur via SSH :**
```bash
cd /var/www/vhosts/votre-domaine.com/httpdocs
# OU selon votre configuration Hostinger
# cd /var/www/vhosts/system/plesk/domains/votre-domaine.com/httpdocs

git pull origin main
```

### 4.2. Exécuter le script de déploiement
```bash
chmod +x deploy-production.sh
./deploy-production.sh
```

**OU manuellement :**
```bash
# Installer les dépendances
composer install --no-dev --optimize-autoloader

# Créer le lien symbolique
php artisan storage:link

# Créer les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrations
php artisan migrate --force

# Permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
```

---

## 🔐 ÉTAPE 5 : Configuration .env

### 5.1. Via Plesk File Manager
```
Plesk → Files → File Manager
```

### 5.2. Créer/modifier .env
```env
APP_NAME="GR-HEVEA"
APP_ENV=production
APP_KEY=base64:... (généré par Laravel)
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=votre_db
DB_USERNAME=votre_user
DB_PASSWORD=votre_password

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=votre-host
MAIL_PORT=587
MAIL_USERNAME=votre-email
MAIL_PASSWORD=votre-password
```

---

## 🚀 ÉTAPE 6 : Test et Vérification

### 6.1. Tester l'application
Visitez : `https://votre-domaine.com`

### 6.2. Vérifier les logs
```bash
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log
```

### 6.3. Tester la performance
```bash
php artisan tinker
>>> \DB::select('SHOW VARIABLES LIKE "innodb_buffer_pool_size"');
>>> \Cache::put('test', 'value', 60);
>>> \Cache::get('test');
```

---

## 📊 Monitoring sur Plesk

### Ressources utilisées
```
Plesk → Tools & Settings → System Updates
Plesk → Tools & Settings → Server Health Monitoring
```

### Logs
```
Plesk → Tools & Settings → Log Browser
```

---

## 🔄 Déploiement futur

**Chaque fois que vous modifiez le code :**

1. **Sur votre PC local :**
```bash
git add .
git commit -m "Vos modifications"
git push origin main
```

2. **Sur le serveur (automatique avec Git hook OU manuel) :**
```bash
cd /var/www/vhosts/votre-domaine.com/httpdocs
git pull origin main
./deploy-production.sh
```

---

## ❗ Troubleshooting Plesk

### Problème : Permissions refusées
```bash
chown -R psacln:psacln storage/
chmod -R 755 storage/
```

### Problème : PHP-FPM ne redémarre pas
```bash
# Via Plesk
Plesk → PHP Settings → Switch PHP handler → PHP-FPM

# OU via SSH
systemctl restart php8.3-fpm
```

### Problème : MySQL lent
```bash
# Vérifier les paramètres
mysql -u root -p
SHOW VARIABLES LIKE 'innodb_buffer_pool_size';
SHOW PROCESSLIST;
```

---

## ✅ Checklist finale

- [ ] PHP 8.3 configuré avec OPcache
- [ ] PHP-FPM optimisé (200 workers)
- [ ] MySQL optimisé (16GB buffer pool)
- [ ] Nginx optimisé (4096 connections)
- [ ] .env configuré (APP_ENV=production)
- [ ] Caches Laravel activés
- [ ] Storage link créé
- [ ] Permissions correctes
- [ ] Base de données importée
- [ ] Application accessible via HTTPS
- [ ] Monitoring activé

**Votre application est prête pour 300 utilisateurs simultanés ! 🎉**

