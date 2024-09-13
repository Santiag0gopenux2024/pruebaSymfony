FROM php:8.3-fpm

RUN apt-get update \
    && apt-get install -y \
        libssl-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libz-dev \
        curl \
        git \
        libzip-dev \
        libpq-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/html
