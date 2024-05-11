FROM node:18-alpine as node

# Build FE first
WORKDIR /app
COPY . .
# Building assets
RUN npm install && npm run build

# Build BE
FROM webdevops/php-nginx:8.2-alpine

# Install Laravel framework system requirements (https://laravel.com/docs/10.x/deployment)
RUN apk add oniguruma-dev postgresql-dev libxml2-dev
# All of this already pre installed.
# Validated by running `docker run --rm webdevops/php-nginx:8.2-alpine php -m`
# RUN docker-php-ext-install \
        # ctype \
        # dom \
        # fileinfo \
        # filter \
        # hash \
        # mbstring \
        # openssl \
        # pcre \
        # pdo \
        # session \
        # tokenizer \
        # json \
        # mbstring \
        # pdo_mysql \
        # pdo_pgsql \
        # xml

# Copy Composer binary from the Composer official Docker image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV WEB_DOCUMENT_ROOT /app/public
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
WORKDIR /app
COPY --from=node /app .

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN composer self-update
RUN composer require laravel/breeze --dev
RUN composer require inertiajs/inertia-laravel
# RUN php artisan migrate --force
RUN chown -R application:application .

FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

CMD ["/start.sh"]
