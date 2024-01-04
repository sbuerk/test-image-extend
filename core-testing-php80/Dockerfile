FROM ghcr.io/typo3/core-testing-php80
ARG TARGETPLATFORM
ARG IMAGE_NAME
ARG IMAGE_VERSION
LABEL org.opencontainers.image.source=https://github.com/sbuerk/test-image-extend
LABEL org.opencontainers.image.description="Extended ghcr.io/typo3/core-testing-php80"
LABEL org.opencontainers.image.licenses=MIT

# We use convenient install-php-extensions script to manage additional php extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN apk add --no-cache \
        bash \
        file \
        ghostscript \
        git \
        graphicsmagick \
        grep \
        patch \
        linux-headers \
        findutils \
    && chmod +x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions \
        ssh2 \
        pcntl \
    # write version information \
    && touch /etc/typo3-image-info.json \
    && php -r '$gv=explode(" ", `git --version`)[2];$core=[];$exts=[];$pv=phpversion(); foreach (get_loaded_extensions() as $extName) {$extVersion = phpversion($extName); if ($pv === $extVersion) {$core[$extName] = $extVersion;} else {$exts[$extName] = $extVersion;}} ksort($core); ksort($exts); echo \json_encode(["name" => getenv("IMAGE_NAME"), "version" => getenv("IMAGE_VERSION"), "platform" => getenv("TARGETPLATFORM"), "php" => $pv, "xdebug" => phpversion("xdebug"), "git" => $gv, "core" => $core, "pecl" => $exts], JSON_PRETTY_PRINT) . PHP_EOL;' > /etc/typo3-image-info.json \
    # cleanup \
    && apk del linux-headers \
    && rm -rf /var/cache/apk/* /tmp/*