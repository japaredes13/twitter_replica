#!/bin/bash

# Instalar las dependencias de Composer
composer install

# Ejecutar migraciones y otros comandos de Artisan
# php artisan migrate --force

php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan key:generate

# Ejecuta PHP-FPM
exec php-fpm