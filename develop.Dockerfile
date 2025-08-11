FROM php:8.2-fpm-alpine

# Arguments defined in docker-compose.yml
ARG user
ARG uid


LABEL Description="Base setup for Aspire Ability."

# Set environment variable for GitLab token
ARG GITLAB_TOKEN
ENV GITLAB_TOKEN=${GITLAB_TOKEN}


# if user want to build without cache layer for docker
# RUN apk add --update --no-cache \
# if user want to build  cache layer for docker
RUN apk add --update \
    $PHPIZE_DEPS \
    git \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    libpq-dev \
    postgresql-dev \
    imagemagick-dev \
    pcre-dev \
    npm \
    nodejs \
    && docker-php-ext-install pdo_mysql bcmath zip exif mysqli pdo_pgsql \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j "$(nproc)" gd \
    && docker-php-ext-install pcntl \
    && apk del $PHPIZE_DEPS

# RUN docker-php-ext-install sockets
RUN apk add --no-cache linux-headers
RUN docker-php-ext-install sockets



RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && chmod +x /usr/bin/composer

# RUN git config --global url."https://${GITLAB_TOKEN}@gitlab.com/".insteadOf "https://gitlab.com/"

# COPY ./.docker/start.sh /usr/local/bin/start

RUN addgroup -S -g $uid $user \
    && adduser -S -D -u $uid -h /home/$user -G www-data $user

RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


WORKDIR /var/www/html


USER $user


