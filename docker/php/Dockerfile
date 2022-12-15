FROM php:8.1-fpm-alpine

# Install DB driver for PHP => pdo pdo_mysql
RUN set -xe \
    && apk add --no-cache --update --virtual .phpize-deps $PHPIZE_DEPS \
    && apk add --update linux-headers \
    && apk add git bash libpng libpng-dev openssh rsync \
    && docker-php-ext-install pdo pdo_mysql gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /usr/share/php \
    && rm -rf /tmp/* \
    && apk del  .phpize-deps

# Override php.ini settings
#COPY ./docker/symfony/app_custom.ini /usr/local/etc/php/conf.d/app_custom.ini

# COPY . /var/www/html

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN curl -L https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# RUN curl --show-error --silent https://getcomposer.org/installer | php -- \
#    --install-dir=/usr/bin --filename=composer && chmod +x /usr/bin/composer && composer install -v

WORKDIR /var/www/html