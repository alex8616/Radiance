FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Activar mod_rewrite (Laravel lo necesita)
RUN a2enmod rewrite

# Configurar Apache para Laravel (carpeta public)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
COPY . /var/www/html/

WORKDIR /var/www/html

# Permisos necesarios
RUN chmod -R 775 storage bootstrap/cache

# Instalar dependencias Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Limpiar caches (evita errores 500)
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true

# Permisos finales
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80