FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev libxml2-dev \
    libsqlite3-dev sqlite3 pkg-config nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip xml ctype \
    && a2enmod rewrite headers

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN npm install && npm run build

RUN mkdir -p /var/www/html/database && touch /var/www/html/database/database.sqlite

RUN chmod -R 777 storage bootstrap/cache /var/www/html/database

ENV DB_DATABASE /var/www/html/database/database.sqlite

ENV APP_URL=https://levrai-rqq7.onrender.com

EXPOSE 80

CMD php artisan config:clear && php artisan migrate --force && apache2-foreground
