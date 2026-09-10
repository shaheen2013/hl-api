FROM php:7.2
MAINTAINER Xisco Lladó <x.llado@hotelinking.com>
EXPOSE 80/tcp
RUN apt-get update && apt-get install -y zip unzip mysql-client \
    && docker-php-ext-install pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer