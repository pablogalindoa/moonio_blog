#!/bin/sh
set -eu
cd "$(dirname "$0")/.."
LAN_IP=${1:-$(ipconfig getifaddr en0)}
if [ -z "$LAN_IP" ]; then echo 'Usage: sh scripts/setup-lan-https.sh LAN_IP'; exit 1; fi
mkdir -p .certs
chmod 700 .certs
mkcert -cert-file .certs/lan.pem -key-file .certs/lan-key.pem localhost 127.0.0.1 ::1 "$LAN_IP"
# Copy ONLY the public CA, never rootCA-key.pem, for transfer to the demo device.
cp "$(mkcert -CAROOT)/rootCA.pem" .certs/moonio-rootCA.crt
python3 - <<'PY'
from pathlib import Path
s=Path('docker/apache/app.conf').read_text()
s=s.replace('ServerName localhost\nPassEnv CI_ENV\n', '')
s=s.replace('<VirtualHost *:80>', '<VirtualHost *:443>\n    SSLEngine on\n    SSLCertificateFile /etc/moonio-certs/lan.pem\n    SSLCertificateKeyFile /etc/moonio-certs/lan-key.pem')
Path('.certs/https.conf').write_text(s)
PY
printf 'Certificate ready. Start: docker compose -f compose.yaml -f compose.https.yaml up -d --build\nDemo URL: https://%s:8443/blog/index.php/login\n' "$LAN_IP"
