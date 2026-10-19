# LAN chat evaluation — 2026-10-19

## Open the application

- Mac: http://localhost:8080/blog/index.php/login
- Second device, same Wi-Fi/LAN: http://192.168.18.14:8080/blog/index.php/login
- Chat: `/blog/index.php/chats`
- phpMyAdmin, Mac only: http://localhost:8081

The IP can change; check `ipconfig getifaddr en0`. Web publishes `0.0.0.0:8080`; phpMyAdmin remains loopback-only. MariaDB and chat publish no host ports. Apache still proxies `/socket.io/` including WebSocket upgrades. Host port 3000 is untouched. Development URLs use `/blog/` so links, uploads and redirects stay on the visiting device's origin.

## Two-device test

1. Log in with two different existing accounts. Open Chats on both devices.
2. Select the same private conversation on both. The existing private chat 1 connects employee IDs 1 and 3. For another pair, use **Nueva conversación**, select exactly one other user and create it; the group name is optional. Reload the other device's chat list once to find a newly created conversation.
3. A sends `EVALUACION-A-<unique time>`. B must see it without refresh.
4. B sends `EVALUACION-B-<same time>`. A must see it without refresh.
5. Refresh both pages, select the same conversation, and verify both messages appear exactly once.
6. Click **📎 Archivo**, choose a small TXT/PDF/image, then open its link on the recipient. Limit: 20 MB; chat uploads accept common documents, images, ZIP, MP3 and MP4. The standalone transfer module remains available.
7. Capture the two chats, all three action buttons, the downloaded file and phpMyAdmin rows as evidence.

## Database evidence

Open **codeigniter → messages → Browse**, sort `id_messages` descending. Inspect:

- `id_messages`: unique saved message ID
- `id_chats`: shared conversation ID
- `id_employee`: sender (different on A and B)
- `message`: exact evaluation marker or file/map link
- `created_at`: server timestamp

Use the SQL tab to find your manual test:

```sql
SELECT id_messages, id_chats, id_employee, message, created_at
FROM messages
WHERE message LIKE 'EVALUACION-%'
ORDER BY id_messages DESC;
```

File and location messages use this same table. Uploaded-file metadata is in `ftp_files` (`id_file`, `file_name`, `file_path`, `uploaded_by`, `file_size`, `created_at`); bytes remain in `uploads/`. File links are relative to the current host so localhost links work for LAN recipients too.

## Automated verification

Passed against running Docker services with two independent Chrome browser contexts using temporary authenticated sessions for existing users 1 and 3 (no password changes; interactive login itself was not automated):

- LAN login page HTTP 200; Socket.IO polling handshake and WebSocket upgrade through Apache.
- Canonical private room isolation: both members received one message; unrelated room received zero even when the client supplied the wrong room field.
- A/B live delivery exactly once, successful persistence, history after browser reload.
- Real file picker/upload controller, message persistence and recipient download with exact matching bytes.
- Clickable map message saved and rendered; this used test coordinates, not real GPS.
- Visible HTTPS requirement when requesting location over LAN HTTP.
- Registered-user offer and end-call routing; actual media not tested.
- Invalid participant rejected by persistence and not acknowledged as saved.
- No browser JavaScript errors during the test.
- PHP lint passed for changed controller and view files; all four containers healthy.
- All 254 original database rows remain byte-for-byte in the SQL export; all 165 original upload files retain their SHA-256 hashes. Only evaluation messages/file were added. No schema changes.

Evidence retained in MariaDB: IDs **25/26**, chat **1**, employees **1/3**, marker `EVAL-1792431932073-A/B`; file message **27**, download `/blog/index.php/ftp/download/4`; map test message **28**. Test artifacts are deliberately retained for review. Temporary exports and browser screenshot are under `/private/tmp/eval-*`, outside Git.

## HTTPS blocker for physical-device camera/GPS

Camera/microphone and geolocation require a secure context. `http://localhost` qualifies on the Mac; `http://192.168.18.14:8080` does not on another device. No insecure flags, certificate-warning bypasses or TURN changes were added.

Minimum trusted local setup:

1. Use a local CA (mkcert is installed on this Mac), install its root certificate in the Mac trust store, and generate a server certificate with SANs for `localhost`, `127.0.0.1` and `192.168.18.14`.
2. Configure Apache TLS with that certificate/key and publish a development HTTPS web port (for example 8443), retaining the same `/socket.io/` proxy. Keep private keys out of Git and the public document root.
3. Install **only the CA public root certificate**, never its private key, into the second device's trusted certificate store. On iOS, install the profile and explicitly enable full trust; other devices/browsers have their own CA trust settings.
4. Open `https://192.168.18.14:8443/blog/index.php/login` only after TLS is configured and trusted; that URL is **not enabled by this phase**. Confirm no certificate warning, then grant site camera/microphone/location permissions.
5. Test **📍 Ubicación**, **📹 Videollamada**, acceptance on the other device, audio/video in both directions and **Colgar**.

Trust installation on the second device cannot be performed from this workspace. HTTPS was left unconfigured to keep this evaluation phase small; two-physical-device media/GPS testing remains blocked until that setup is completed. Text/file sharing works over the provided HTTP URLs. See [MDN secure contexts](https://developer.mozilla.org/en-US/docs/Web/Security/Defenses/Secure_Contexts) and [getUserMedia](https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia).

Existing authentication/signaling architecture is retained; this is a LAN development evaluation, not an Internet deployment.
