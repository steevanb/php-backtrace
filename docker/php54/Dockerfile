FROM php:5.4.45-cli

COPY --from=composer:2.0.13 /usr/bin/composer /usr/local/bin/composer
COPY composer.json /root/.config/composer/

RUN \
    apt update \
    && apt install -y git zip \
    # To make composer global update works
    && mkdir /app \
    && composer global update --no-dev \
    && ln -s /root/.config/composer/vendor/bin/phpunit /usr/local/bin/phpunit

WORKDIR /app
