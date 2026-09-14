#!/bin/sh
set -eu

if [ ! -d /var/lib/mysql/mysql ]; then
    if [ ! -s /seed/current_codeigniter.sql ]; then
        echo "The verified application/database/current_codeigniter.sql export is required." >&2
        exit 1
    fi
    # The host export stays read-only and mode 0600. Only a container-local
    # tmpfs copy is made readable by the official entrypoint's mysql user.
    cp /seed/current_codeigniter.sql /docker-entrypoint-initdb.d/010-codeigniter.sql
    chmod 644 /docker-entrypoint-initdb.d/010-codeigniter.sql
fi

exec /usr/local/bin/docker-entrypoint.sh "$@"
