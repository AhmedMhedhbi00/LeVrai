FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev libxml2-dev \
    libsqlite3-dev sqlite3 pkg-config \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip xml ctype \
    && a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite

RUN chmod -R 777 storage bootstrap/cache /var/www/html/database

RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV DB_DATABASE /var/www/html/database/database.sqlite

EXPOSE 80

CMD php artisan migrate --force && apache2-foreground
