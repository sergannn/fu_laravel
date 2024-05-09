# Use NGINX Unit image as the base image
FROM nginx/unit:1.18.0-php7.3

# Install Node.js and Composer
RUN apk add --no-cache nodejs npm python3 py3-pip && \
    npm install -g n && \
    n latest && \
    npm install -g composer

# Copy your Laravel Breeze application code into the Docker image
COPY. /var/apphome

# Set the working directory
WORKDIR /var/apphome

# Install Laravel Breeze dependencies
RUN composer install

# Install Node.js dependencies
RUN npm install

# Compile assets
RUN npm run dev

# Configure NGINX Unit
COPY nginx-unit.conf /etc/nginx/unit.conf

# Expose the necessary ports
EXPOSE 80 443

# Start NGINX Unit
CMD ["nginx-unit", "-c", "/etc/nginx/unit.conf"]
