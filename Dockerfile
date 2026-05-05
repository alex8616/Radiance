FROM php:8.2-apache

# =========================
# Dependencias del sistema
# =========================
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# =========================
# Apache (Laravel /public)
# =========================
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# =========================
# Composer
# =========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =========================
# Copiar proyecto
# =========================
WORKDIR /var/www/html
COPY . /var/www/html/

# =========================
# Instalar dependencias
# =========================
# Instalamos sin ejecutar scripts de Laravel aún para evitar errores de entorno
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# =========================
# Permisos finales (CRÍTICO)
# =========================
# Cambiamos el dueño a www-data y damos permisos de escritura a storage y cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# =========================
# Comando de Inicio
# =========================
# Limpiamos caché en tiempo de ejecución para asegurar que lea las variables de Render
CMD php artisan optimize:clear && apache2-foreground