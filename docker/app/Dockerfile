### Base Image
FROM php:7.3-fpm AS stage
RUN apt-get update && apt-get install -y \
    wget \
    vim \
    git \
    zlib1g-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql opcache
# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

### Composer
FROM composer:1.8.5 AS build 
WORKDIR /app 
COPY . /app 
ENV COMPOSER_ALLOW_SUPERUSER=1 
RUN composer global require hirak/prestissimo --quiet
RUN composer install --quiet

### Production
FROM stage as production
COPY --from=build /app /app 

### Production Commands
WORKDIR /app
COPY ./docker/app/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN cp .env.prod .env
RUN php artisan key:generate
RUN php artisan jwt:secret
RUN chmod -R 777 storage/* bootstrap/cache
RUN php artisan optimize