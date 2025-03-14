FROM php:8.3-fpm-bullseye
ARG TARGETPLATFORM
ARG IMAGE_NAME
ARG IMAGE_VERSION
LABEL org.opencontainers.image.source=https://github.com/sbuerk/test-image-extend
LABEL org.opencontainers.image.description="Extended ghcr.io/typo3/core-testing-php83"
LABEL org.opencontainers.image.licenses=MIT

# Use convenient install-php-extensions script to manage additional php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apt-get update \
&& apt-get install --yes git openssh-client \
&& chown -R www-data:www-data /var/www \
&& chmod +x /usr/local/bin/install-php-extensions \
&& sync \
&& install-php-extensions \
  bcmath \
  sockets \
  @composer-2 \
  intl \
  mbstring \
  opcache \
&& mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"