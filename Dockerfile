FROM php:8.0-apache

WORKDIR /var/www/html

RUN pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug
RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY php.ini /usr/local/etc/php/php.ini
COPY index.php .