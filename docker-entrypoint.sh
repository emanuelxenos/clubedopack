#!/bin/sh
set -e

# Cachear configurações, rotas e views para otimizar performance em produção
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rodar migrações do banco de dados automaticamente
php artisan migrate --force

# Iniciar o PHP-FPM em background e depois o Nginx em foreground
php-fpm -D
nginx -g "daemon off;"
