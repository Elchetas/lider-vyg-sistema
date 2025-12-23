FROM php:8.2-apache

# Extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Habilitar rewrite
RUN a2enmod rewrite

# 👉 APLICACIÓN REAL
ENV APACHE_DOCUMENT_ROOT /var/www/html/overlay

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

# Copiar proyecto
COPY . /var/www/html

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
