FROM php:7.3.6-fpm-alpine
RUN apk update && apk add --no-cache \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        mbstring \
        pdo
RUN mkdir /.composer \
    && chown -R www-data:www-data /.composer
ENV PATH /var/www/html/bin:/var/www/html/vendor/bin:$PATH