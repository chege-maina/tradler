FROM php:8.2-fpm-alpine

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN set -ex \
    	&& apk --no-cache add mysql-dev nodejs npm libzip-dev zip \
    	&& docker-php-ext-install pdo pdo_mysql zip mysqli && docker-php-ext-enable mysqli

WORKDIR /var/www/html

COPY . .

RUN chmod 755 /var/www/html