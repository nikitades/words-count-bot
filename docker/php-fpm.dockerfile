FROM php:7.3.8-fpm
RUN docker-php-ext-install pdo pdo_mysql mysqli