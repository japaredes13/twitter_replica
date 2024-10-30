# Usar la imagen base de PHP 8.1 FPM
FROM php:8.1-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensiones de PHP necesarias
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath

# Instalar y configurar Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuración de Xdebug
COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/twitter

# Crear un usuario www-data
RUN usermod -u 1000 www-data

# Copiar los archivos del proyecto
COPY . /var/www/twitter/

# Copy existing application directory permissions
COPY --chown=www-data:www-data . .

# Copiar el script de entrada
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Cambiar permisos en el directorio de almacenamiento
RUN chown -R www-data:www-data /var/www/ \
    && chmod -R 777 /var/www/twitter/storage /var/www/twitter/bootstrap/cache

# Change current user to www
USER www-data

# Exponer el puerto 9000 para PHP-FPM y el puerto 9003 para el debbug
EXPOSE 9000 9003

ENTRYPOINT [ "entrypoint.sh" ]

# Ejecutar PHP-FPM en primer plano
CMD ["php-fpm"]
