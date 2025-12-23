FROM php:8.2-apache

# Instalar extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql

# Habilitar mod_rewrite
RUN a2enmod rewrite

# 👉 DOCUMENT ROOT CORRECTO (Laravel)
ENV APACHE_DOCUMENT_ROOT /var/www/html/overlay/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copiar proyecto
COPY . /var/www/html

# Crear carpetas necesarias y permisos
RUN mkdir -p /var/www/html/overlay/storage \
    /var/www/html/overlay/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/overlay/storage /var/www/html/overlay/bootstrap/cache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Instalar dependencias (composer.json está en la raíz)
WORKDIR /var/www/html/overlay
RUN composer install

EXPOSE 80

# Ejecutar migraciones sin seed y arrancar Apache
CMD php artisan migrate --force || true && apache2-foreground
