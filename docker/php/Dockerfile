FROM php:7.2.12-fpm-alpine
RUN apk update && apk add --no-cache \
    zlib-dev \
    zip \
    postgresql-dev \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        mbstring \
        zip \
        pdo \
        pdo_pgsql
RUN mkdir /.composer \
    && chown -R www-data:www-data /.composer
ENV PATH /var/www/html/bin:/var/www/html/vendor/bin:$PATH