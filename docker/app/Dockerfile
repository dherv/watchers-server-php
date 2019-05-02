### Base Image
FROM php:7.3-fpm AS stage
RUN apt-get update && apt-get install -y \
    wget \
    curl \
    cron \
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
RUN cp .env.prod .env
# Copy hello-cron file to the cron.d directory
COPY ./cron/fetch-cron /etc/cron.d/fetch-cron
# Give execution rights on the cron job
RUN chmod 0644 /etc/cron.d/fetch-cron
# Apply cron job
RUN crontab /etc/cron.d/fetch-cron
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
# Remove pam_loginuid.so to prevent pam security issue
RUN sed -i '/session    required     pam_loginuid.so/c\#session    required   pam_loginuid.so' /etc/pam.d/cron
# # Run the command on container startup
# CMD cron && tail -f /var/log/cron.log
RUN php artisan key:generate
RUN php artisan jwt:secret
RUN chmod -R 777 storage/* bootstrap/cache
# RUN php artisan optimize