FROM php:8.2-apache-bookworm

ARG LOCAL_UID=1000
ARG LOCAL_GID=1000

# curl, openssl, fileinfo and mbstring are compiled into this official image.
RUN docker-php-ext-install -j"$(nproc)" mysqli \
    && php -r 'foreach (["mysqli", "curl", "openssl", "fileinfo", "mbstring", "session", "json", "iconv"] as $extension) { if (!extension_loaded($extension)) { fwrite(STDERR, "Missing extension: $extension\n"); exit(1); } }' \
    && groupmod --non-unique --gid "${LOCAL_GID}" www-data \
    && usermod --non-unique --uid "${LOCAL_UID}" --gid "${LOCAL_GID}" www-data \
    && a2enmod proxy proxy_http proxy_wstunnel headers \
    && a2dissite 000-default

COPY docker/php/php.ini /usr/local/etc/php/conf.d/moonio.ini
COPY docker/apache/app.conf /etc/apache2/sites-enabled/moonio.conf
COPY docker/php/entrypoint.sh /usr/local/bin/moonio-web-entrypoint
COPY docker/php/healthcheck.php /usr/local/bin/moonio-web-healthcheck.php
RUN chmod 755 /usr/local/bin/moonio-web-entrypoint

WORKDIR /var/www/html/blog
ENTRYPOINT ["moonio-web-entrypoint"]
CMD ["apache2-foreground"]
