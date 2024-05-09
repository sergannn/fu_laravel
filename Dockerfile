ARG UNIT_PHP8_IMAGE=docker.io/library/unit:1.31.0-php8.2

FROM ${UNIT_PHP8_IMAGE}

ENV APP_ROOT=/opt/app-root \
    server_port=8080 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=1

# Install Deps
RUN apt-get update && \
    apt-get install -y libpq-dev zip curl && \
    apt-get clean && rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-install pdo pdo_pgsql opcache && \
  pecl install redis && docker-php-ext-enable redis && \
    curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer


RUN useradd -u 1001 -r -g 0 -d /opt/app-root -s /sbin/nologin \
      -c "Default Application User" default && \
    mkdir -p $APP_ROOT/app $APP_ROOT/run $APP_ROOT/etc && \
    chown 1001:0 -R $APP_ROOT

    COPY docker/scripts/opcache.ini "$PHP_INI_DIR/conf.d/opcache.ini"
COPY docker/scripts/config.json $APP_ROOT/etc/config.json
COPY --chmod=0555 docker/scripts/docker-entrypoint.sh /docker-entrypoint.sh
COPY --chown=1001:0 . $APP_ROOT/app

WORKDIR $APP_ROOT
USER 1001

# Build App
RUN cd app && \
    composer install --no-dev && \
    composer clear-cache

ENTRYPOINT ["/docker-entrypoint.sh"]
