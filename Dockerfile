FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Activar mod_rewrite (IMPORTANTE para Laravel)
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . /var/www/html/

WORKDIR /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html

# Instalar dependencias Laravel
RUN composer install --no-interaction --optimize-autoloader

EXPOSE 80