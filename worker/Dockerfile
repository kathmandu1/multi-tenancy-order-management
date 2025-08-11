FROM php:8.2-fpm-alpine
ARG user
ARG uid

LABEL Description="Base setup for  queue development."

# RUN apk add --update --no-cache \
RUN apk add --update \
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
    && apk del $PHPIZE_DEPS


# Add non-root user, prevents files being created with root permissions on host machine
ARG PUID=1000
ENV PUID ${PUID}
ARG PGID=1000
ENV PGID ${PGID}

RUN addgroup -S -g 1000 php \
    && adduser -S -D -u 1000 -h /home/php -G www-data 1000


WORKDIR /var/www/html

# EXPOSE 6001

CMD ["php", "artisan", "queue:work"]
# CMD ["php artisan queue:work"]



