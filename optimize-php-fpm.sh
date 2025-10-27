#!/bin/bash
# Script d'optimisation PHP-FPM pour 300 utilisateurs
# À exécuter après installation sur le serveur

set -e

PHP_VERSION="8.3"
FPM_CONFIG="/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"

echo "🔧 Optimisation PHP-FPM pour haute performance"
echo "=============================================="

# Backup de la configuration actuelle
cp "$FPM_CONFIG" "${FPM_CONFIG}.backup"

# Configuration optimale pour 32GB RAM
cat > /tmp/fpm_optimization.txt << 'EOF'
# Configuration optimisée pour 300 utilisateurs simultanés - 32GB RAM

# Process manager
pm = dynamic
pm.max_children = 200
pm.start_servers = 50
pm.min_spare_servers = 30
pm.max_spare_servers = 80
pm.max_requests = 1000

# Performance
pm.process_idle_timeout = 60s
request_terminate_timeout = 180

# Mémoire
php_admin_value[memory_limit] = 512M
php_admin_value[max_execution_time] = 180
php_admin_value[max_input_time] = 180

# Security
php_admin_value[upload_max_filesize] = 100M
php_admin_value[post_max_size] = 100M

# Optimization
php_admin_value[realpath_cache_size] = 10M
php_admin_value[realpath_cache_ttl] = 3600
EOF

echo "📝 Appliquer cette configuration dans $FPM_CONFIG"
echo "   N'oubliez pas de redémarrer: systemctl restart php${PHP_VERSION}-fpm"
echo ""
echo "✅ Configuration sauvegardée dans ${FPM_CONFIG}.backup"

