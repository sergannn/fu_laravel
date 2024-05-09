# Use NGINX Unit image as the base image
FROM nginx/unit:1.18.0-php7.3

# Install Node.js and npm
RUN apk add --no-cache nodejs npm
