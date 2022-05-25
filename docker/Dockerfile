FROM php:8.1.0-fpm-alpine3.15

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN apk add icu-dev \
    && apk add --virtual .phpize-deps $PHPIZE_DEPS

RUN pecl install xdebug

RUN apk add rabbitmq-c-dev && \
    pecl install amqp

RUN apk del .phpize-deps

RUN docker-php-ext-install intl && \
    docker-php-ext-enable xdebug amqp

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

WORKDIR /var/www

CMD [ "php-fpm", "-R" ]
