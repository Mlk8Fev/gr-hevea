#!/bin/bash
# Script d'optimisation MySQL/MariaDB pour 300 utilisateurs - 32GB RAM
# À exécuter sur le serveur

set -e

echo "🗄️ Optimisation MySQL/MariaDB pour haute performance"
echo "===================================================="

MYSQL_CONFIG="/etc/mysql/mariadb.conf.d/50-server.cnf"

# Backup
cp "$MYSQL_CONFIG" "${MYSQL_CONFIG}.backup"

cat > /tmp/mysql_optimization.txt << 'EOF'
# Optimisation MySQL pour 32GB RAM - 300 utilisateurs simultanés

[mysqld]
# Buffer Pool - 50% de la RAM disponible
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
read_buffer_size = 2M
read_rnd_buffer_size = 4M

# Réseau
max_allowed_packet = 64M
interactive_timeout = 300
wait_timeout = 300
EOF

echo "📝 Appliquer cette configuration dans $MYSQL_CONFIG"
echo "   N'oubliez pas de redémarrer: systemctl restart mysql"
echo ""
echo "✅ Configuration sauvegardée dans ${MYSQL_CONFIG}.backup"

