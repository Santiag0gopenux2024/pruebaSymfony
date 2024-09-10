FROM php:8.3-fpm

# Instalación de dependencias y extensiones
RUN apt-get update \
    && apt-get install -y \
        libssl-dev \
        libcurl4-openssl-dev \
        pkg-config \
        libz-dev \
        curl \
        git \
    && rm -rf /var/lib/apt/lists/*

# Instalación de Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer

# Instalación de la extensión MongoDB
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Instalación de la extensión Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuración del directorio de trabajo
WORKDIR /var/www/html