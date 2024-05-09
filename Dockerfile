FROM node:10-alpine

RUN mkdir -p /home/node/app/node_modules && chown -R node:node /home/node/app

WORKDIR /home/node/app

COPY package.json ./

USER root

RUN npm install
RUN apk update
RUN apk add --no-cache curl php php-cli php-curl php-json php-mbstring php-openssl php-tokenizer php-zlib && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#RUN apk add --no-cache curl && \
#    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
