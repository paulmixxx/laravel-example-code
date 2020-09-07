FROM php:7.2-fpm

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN apt-get update -y && apt-get install wget nano zip unzip libaio1 -y
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet
RUN chmod +x ./composer.phar \
    && mv ./composer.phar /usr/local/bin/composer \
    && composer --version

RUN docker-php-ext-install pdo_mysql