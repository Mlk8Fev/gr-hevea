#!/bin/bash
# Script de déploiement optimisé pour production
# Pour 300 utilisateurs simultanés - 32GB RAM

set -e

echo "🚀 Déploiement GR-HEVEA Production Optimisé"
echo "=============================================="

# Variables - Ajustez selon votre configuration Plesk
APP_DIR="/var/www/vhosts/system/plesk/domains/votre-domaine.com/httpdocs"
# OU pour Hostinger
APP_DIR="/var/www/vhosts/votre-domaine.com/httpdocs"
PHP_VERSION="8.3"

cd "$APP_DIR"

# 1. Backup avant déploiement
echo "📦 Création du backup..."
tar -czf ~/backup-$(date +%Y%m%d-%H%M%S).tar.gz ./

# 2. Mise à jour du code
echo "📥 Mise à jour du code..."
git pull origin main

# 3. Installer les dépendances production uniquement
echo "📚 Installation des dépendances..."
composer install --no-dev --optimize-autoloader --classmap-authoritative

# 4. Optimiser l'autoloader
echo "⚡ Optimisation de l'autoloader..."
composer dump-autoload --optimize --classmap-authoritative

# 5. Configurer .env production
if [ ! -f .env ]; then
    echo "📝 Configuration .env..."
    cp .env.example .env
    php artisan key:generate
fi

# 6. Migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# 7. Seeds (seulement si nécessaire)
# php artisan db:seed --force

# 8. Créer le lien symbolique storage
echo "🔗 Création du lien symbolique storage..."
php artisan storage:link

# 9. Créer les caches optimisés
echo "💾 Création des caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 10. Permissions
echo "🔐 Configuration des permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/

# 11. Nettoyer les caches obsolètes
echo "🧹 Nettoyage des caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 12. Recréer les caches
echo "💾 Recréation des caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 13. Clear OPcache
echo "🗑️ Nettoyage de l'OPcache..."
systemctl restart php${PHP_VERSION}-fpm

# 14. Redémarrer Octane (si installé)
if command -v php && php artisan octane:status &>/dev/null; then
    echo "🔄 Redémarrage d'Octane..."
    php artisan octane:reload
fi

# 15. Tester la connexion DB
echo "✅ Test de la connexion à la base de données..."
php artisan migrate:status

# 16. Health check
echo "🏥 Health check..."
php artisan route:list --compact > /dev/null

echo ""
echo "✅ Déploiement terminé avec succès !"
echo "🎉 Application prête pour 300 utilisateurs simultanés"
echo ""
echo "📊 Statistiques:"
echo "   - RAM disponible: 32GB"
echo "   - PHP version: $(php -r 'echo PHP_VERSION;')"
echo "   - Composer: $(composer --version)"
echo ""

