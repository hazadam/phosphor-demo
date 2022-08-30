FROM node:lts as frontend-build

RUN mkdir -p /build/public/build
WORKDIR /build
COPY yarn.lock webpack.config.js package.json ./
COPY assets ./assets/
COPY templates ./templates/
RUN yarn install && yarn build

FROM php:8.1-apache as php-vendor

RUN mkdir -p /build/public/build
WORKDIR /build
COPY composer.lock composer.json ./
RUN apt update && apt install --yes --no-install-recommends libzip-dev libzip4 && \
    docker-php-ext-install zip && \
    curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN /usr/local/bin/composer install --no-scripts --no-dev --optimize-autoloader

FROM php:8.1-apache as dist

RUN mkdir -p /var/www/html/app/public
WORKDIR /var/www/html/app
COPY .env composer.json ./
COPY src ./src
COPY templates ./templates
COPY config ./config
COPY bin ./bin
COPY public/index.php ./public
COPY --from=frontend-build /build/public/build ./public/build
COPY --from=php-vendor /build/vendor ./vendor
RUN ./bin/console cache:clear && ./bin/console cache:warmup && ./bin/console assets:install public
