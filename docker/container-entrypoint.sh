#!/bin/sh
set -eu

cd /var/www/html

rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf
ln -s ../mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load

app_port="${PORT:-10000}"
sed -ri "s/Listen [0-9]+/Listen ${app_port}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${app_port}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

find storage/framework/views -type f -name '*.php' -delete
php artisan view:clear
php artisan config:clear

if [ ! -d storage/app/public/products ] || [ -z "$(find storage/app/public/products -type f -print -quit)" ]; then
    mkdir -p storage/app/public/products
    cp -a /opt/kaizen-product-assets/products/. storage/app/public/products/
fi

chown -R www-data:www-data bootstrap/cache storage
php artisan storage:link --force

attempt=0
until php -r '
    try {
        $host = getenv("DB_HOST");
        $port = getenv("DB_PORT") ?: "3306";
        $database = getenv("DB_DATABASE");
        $username = getenv("DB_USERNAME");
        $password = getenv("DB_PASSWORD");
        if (!preg_match("/^[A-Za-z0-9_]+$/", $database)) {
            exit(1);
        }
        $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password, [PDO::ATTR_TIMEOUT => 2]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    } catch (Throwable $exception) {
        exit(1);
    }
' >/dev/null 2>&1; do
    attempt=$((attempt + 1))
    if [ "$attempt" -ge 60 ]; then
        echo "MySQL did not become available in time." >&2
        exit 1
    fi
    sleep 2
done

php artisan migrate --force

php docker/seed-if-empty.php

exec "$@"