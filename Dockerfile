# First stage: Install Node.js and npm
FROM ubuntu AS nodejs
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y nodejs npm

# Second stage: Use the Nginx PHP image and copy Node.js from the first stage
FROM richarvey/nginx-php-fpm:3.1.6

# Copy Node.js and npm from the nodejs stage
COPY --from=nodejs /usr/bin/node /usr/bin/node
COPY --from=nodejs /usr/bin/npm /usr/bin/npm

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
