#!/bin/sh
set -eu

# Only container runtime storage is prepared. Never chmod/chown host uploads.
for directory in /var/lib/php/sessions /var/lib/php/uploads /var/log/moonio /var/cache/moonio; do
    mkdir -p "$directory"
    chown www-data:www-data "$directory"
    chmod 700 "$directory"
done

exec docker-php-entrypoint "$@"
