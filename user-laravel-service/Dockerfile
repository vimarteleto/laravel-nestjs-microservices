FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo_mysql

RUN docker-php-ext-enable mysqli pdo_mysql

RUN apt-get update && apt-get install -y zlib1g-dev libzip-dev unzip

RUN apt-get update && apt-get upgrade -y

WORKDIR /var/www/laravel

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite && service apache2 restart

COPY . .