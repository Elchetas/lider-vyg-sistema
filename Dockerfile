FROM php:8.2-apache

# -----------------------------
# 1. Dependencias del sistema
# -----------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# -----------------------------
# 2. Apache + Laravel public
# -----------------------------
RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT=/var/www/html/overlay/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# -----------------------------
# 3. Composer (ANTES de usarlo)
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# 4. Copiar proyecto
# -----------------------------
COPY . /var/www/html

# -----------------------------
# 5. Permisos Laravel
# -----------------------------
RUN mkdir -p /var/www/html/overlay/storage \
    /var/www/html/overlay/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/overlay/storage \
    /var/www/html/overlay/bootstrap/cache

# -----------------------------
# 6. Instalar dependencias Laravel
# composer.json está en la RAÍZ
# -----------------------------
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# 7. Puerto
# -----------------------------
EXPOSE 80

# -----------------------------
# 8. Arranque seguro
# (NO ejecutar migrate aquí)
# -----------------------------
CMD ["apache2-foreground"]
