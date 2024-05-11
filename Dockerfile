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
COPY scripts/ /scripts/
RUN chmod +x scripts/00-laravel-deploy.sh

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN composer self-update
RUN composer require laravel/breeze --dev
RUN composer require inertiajs/inertia-laravel
RUN php artisan migrate --force
RUN chown -R application:application .
