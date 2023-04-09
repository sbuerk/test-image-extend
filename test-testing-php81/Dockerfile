FROM sbuerk/base-testing-php81
LABEL org.opencontainers.image.source=https://github.com/sbuerk/test-image-extend

# We use convenient install-php-extensions script to manage additional php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
        bash \
        file \
        git \
        graphicsmagick \
        grep \
        patch \
        linux-headers \
    && chmod +x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions \
        ssh2 \
    && echo "BUILD: $( date ) " >> /etc/extended-builddate \
    && apk del linux-headers \
    && rm -rf /var/cache/apk/* /tmp/*