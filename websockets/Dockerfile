FROM php:8.1.13-fpm-alpine

ARG user
ARG uid

LABEL Description="Base setup for  websocket development."

RUN apk add --update --no-cache \
    $PHPIZE_DEPS \
    freetype-dev \
    libpq-dev \
    libzip-dev \
    imagemagick-dev \
    pcre-dev \
    libevent \
    libevent-dev \
    openssl-dev \
    gmp-dev \
    icu-dev \
    && docker-php-ext-install pdo_mysql bcmath zip exif mysqli \
    # && docker-php-ext-configure gd --with-jpeg --with-freetype \
    # && docker-php-ext-install -j "$(nproc)" gd tokenizer sockets \
     && docker-php-ext-install -j "$(nproc)" gd  sockets \
    && pecl install redis xdebug-3.1.6 \
    && docker-php-ext-enable redis xdebug \
    && apk del $PHPIZE_DEPS


# USER $user

# Add non-root user, prevents files being created with root permissions on host machine
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

# RUN addgroup -S -g ${PGID} www-data && \
#     usermod -u ${PUID} www-data

# USER www-data

RUN addgroup -S -g 1000 php \
    && adduser -S -D -u 1000 -h /home/php -G www-data 1000




WORKDIR /var/www/html

EXPOSE 6001

CMD ["php", "artisan", "websockets:serve"]
# CMD ["php artisan queue:work"]



