FROM php:8.2-apache

# =========================
# Dependencias del sistema
# =========================
RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# =========================
# Apache (Configuración Laravel)
# =========================
RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# =========================
# Composer
# =========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =========================
# Copiar Proyecto
# =========================
WORKDIR /var/www/html
COPY . /var/www/html/

# =========================
# Instalación de Dependencias
# =========================
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# =========================
# Permisos y Script de Inicio
# =========================
# 1. Aseguramos que el script de entrada sea ejecutable
# 2. Damos propiedad de los archivos al usuario de Apache
RUN chmod +x /var/www/html/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Usamos ENTRYPOINT para ejecutar el script al arrancar el contenedor
ENTRYPOINT ["/var/www/html/entrypoint.sh"]