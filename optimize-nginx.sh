#!/bin/bash
# Script d'optimisation Nginx pour 300 utilisateurs
# À exécuter sur le serveur

set -e

echo "🌐 Optimisation Nginx pour haute performance"
echo "==========================================="

NGINX_CONFIG="/etc/nginx/nginx.conf"

# Backup
cp "$NGINX_CONFIG" "${NGINX_CONFIG}.backup"

cat > /tmp/nginx_optimization.txt << 'EOF'
# Optimisation Nginx pour 300 utilisateurs - 32GB RAM

user www-data;
worker_processes auto;
worker_rlimit_nofile 65535;
pid /run/nginx.pid;

events {
    worker_connections 4096;
    use epoll;
    multi_accept on;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;
    
    # Logs
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;
    
    # Performance
    sendfile on;
    tcp_nopush on;
    tcp_nodelay on;
    keepalive_timeout 65;
    keepalive_requests 100;
    types_hash_max_size 2048;
    server_tokens off;
    
    # Buffers
    client_body_buffer_size 128k;
    client_max_body_size 100M;
    client_header_buffer_size 1k;
    large_client_header_buffers 4 4k;
    output_buffers 1 32k;
    postpone_output 1460;
    
    # Timeouts
    client_body_timeout 12;
    client_header_timeout 12;
    send_timeout 10;
    
    # Gzip
    gzip on;
    gzip_vary on;
    gzip_proxied expired no-cache no-store private auth;
    gzip_min_length 1000;
    gzip_comp_level 6;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript application/x-javascript;
    
    # Cache statique
    open_file_cache max=65000 inactive=20s;
    open_file_cache_valid 30s;
    open_file_cache_min_uses 2;
    open_file_cache_errors on;
    
    # Rate limiting
    limit_conn_zone $binary_remote_addr zone=conn_limit_per_ip:10m;
    limit_req_zone $binary_remote_addr zone=req_limit_per_ip:10m rate=20r/s;
    
    include /etc/nginx/conf.d/*.conf;
    include /etc/nginx/sites-enabled/*;
}
EOF

echo "📝 Appliquer cette configuration dans $NGINX_CONFIG"
echo "   N'oubliez pas de tester: nginx -t"
echo "   Et redémarrer: systemctl restart nginx"
echo ""
echo "✅ Configuration sauvegardée dans ${NGINX_CONFIG}.backup"

