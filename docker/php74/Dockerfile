FROM php:7.4.19-cli-alpine3.13

COPY --from=composer:2.0.13 /usr/bin/composer /usr/local/bin/composer
COPY composer.json /root/.composer/

RUN \
    apk add git zip \
    # To make composer global update works
    && mkdir /app \
    && composer global update --no-dev \
    && ln -s /root/.composer/vendor/bin/phpunit /usr/local/bin/phpunit

WORKDIR /app
