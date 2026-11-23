#!/usr/bin/env python3
"""Create local-only development credentials without replacing an existing .env."""
from pathlib import Path
import os
import base64
import secrets

root = Path(__file__).resolve().parent.parent
os.umask(0o077)
with (root / '.env').open('x') as output:
    output.write('\n'.join([
        '# Private Docker development settings; do not commit.',
        'DB_USER=moonio',
        'MESSAGE_ENCRYPTION_KEY=' + base64.b64encode(secrets.token_bytes(32)).decode(),
        'SMTP_USER=',
        'SMTP_PASSWORD=',
        'DB_PASSWORD=' + secrets.token_urlsafe(32),
        'DB_ROOT_PASSWORD=' + secrets.token_urlsafe(32),
        'LOCAL_UID=' + str(os.getuid()),
        'LOCAL_GID=' + str(os.getgid()),
        'WEB_PORT=8080',
        'PHPMYADMIN_PORT=8081',
        'APP_BASE_URL=http://localhost:8080/blog/',
        'CHAT_ALLOWED_ORIGIN=http://localhost:8080',
        '',
    ]))
print('Created ignored .env with private credentials and this host\'s UID/GID.')
