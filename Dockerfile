FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip curl libpq-dev libzip-dev zip \
    && docker-php-ext-install pdo pdo_pgsql zip

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Crear Laravel automáticamente
RUN composer create-project laravel/laravel .

# Copiar tu sistema encima
COPY . .

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache

# Variables necesarias
ENV APP_ENV=production
ENV APP_DEBUG=false

# Apache apunta a public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf

# Ejecutar TODO automáticamente al iniciar
CMD php artisan key:generate \
 && php artisan migrate --force --seed \
 && apache2-foreground
