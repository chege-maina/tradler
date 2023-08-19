FROM php:8.2-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN set -ex \
    	&& apk --no-cache add mysql-dev nodejs npm libzip-dev zip \
    	&& docker-php-ext-install pdo pdo_mysql zip

WORKDIR /var/www/html

COPY . .

RUN chmod o+w /var/www