FROM php:8.1.12-apache
COPY src/ /var/www/html/
RUN apt-get update && docker-php-ext-install pdo_mysql
