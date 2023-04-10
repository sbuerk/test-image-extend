FROM typo3/core-testing-php73
LABEL org.opencontainers.image.source=https://github.com/sbuerk/test-image-extend
LABEL org.opencontainers.image.description="Extended typo3/core-testing-php73"
LABEL org.opencontainers.image.licenses=MIT

# We use convenient install-php-extensions script to manage additional php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
        linux-headers \
        mysql-client \
    && chmod +x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions \
        ssh2 \
    && apk del linux-headers \
    && rm -rf /var/cache/apk/* /tmp/*