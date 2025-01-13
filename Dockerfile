FROM node:16 as frontend-build

WORKDIR /app
COPY react-app/package*.json ./
RUN npm install
COPY react-app/ .
RUN npm run build
# Now we have compiled/static files in /app/build


FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libzip-dev default-mysql-client redis-tools \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo pdo_mysql zip gd \
    && curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && a2enmod rewrite

WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json ./
COPY react-app ./react-app
RUN cd react-app && npm install && npm run dev && cp -r public/* ../public

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --optimize-autoloader

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/var

RUN chmod -R 775 /var/www/html/var

RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
  /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]