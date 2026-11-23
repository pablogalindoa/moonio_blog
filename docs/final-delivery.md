# Final delivery audit — 23 November 2026

The current Docker application is ready for the manual demonstration below. Automated tests passed; real camera/microphone/GPS on another device and a delivered email still need a human check. No database reset, framework replacement, theme redesign, TURN service, or browser security bypass was used.

## Evidence and rubric matrix

Legend: ✅ complete in the tested application; ⚠️ partial; ❌ missing; 🧪 physical/manual verification remains. “Complete” does not claim production hardening or a physical-device test. The browser suite uses four independent cookie/session contexts, not four tabs sharing one login.

| Requirement | Status | Implementation and evidence/test | Relevant files | Manual action needed / exact remaining behavior |
|---|---|---|---|---|
| 1. Team size ≤3 | 🧪 | Organizational requirement; no reliable roster in the inspected project | Delivery cover sheet | List the actual 1–3 team members; no code change |
| 2. Automatic connection | ✅ | Chat initializes `io(origin)` automatically. A transport drop while offline was followed by automatic reconnect, registration, room rejoin and history reload, without duplicate message elements | `application/views/home/chats2_v.php`, `chat-server/server.js` | None for automated proof |
| 3. Registration with email | ✅ | Existing Registrar form reused; required/valid/duplicate email and password validation; password_hash/password_verify; optional photo; four independent registrations/logins tested. Fixed relative-base redirect and missing validation-view data | `application/controllers/frontend/EmployeeController.php`, `application/views/frontend/create.php`, `application/views/frontend/login.php` | Register a professor/demo account if desired. No email-address ownership verification is implemented |
| 4. Private chat | ✅ | A→B and B→A without refresh; C forbidden from history; no message delivered to C/D; persistence and logout/login history verified | `chats2_v.php`, `ChatController.php`, `ChatModel.php`, `chat-server/server.js` | Demonstrate on two devices |
| 5. Private video call | 🧪 | Offer/answer/ICE, connected RTCPeerConnection, remote audio+video tracks and hang-up stopping tracks passed with generated media. Native getUserMedia hangs in this headless Mac test environment. Trusted HTTPS and WebSocket work on localhost and LAN IP | `chats2_v.php`, `server.js`, `compose.https.yaml`, `scripts/setup-lan-https.sh`, `docker/apache/app.conf`, `docker/php/Dockerfile` | Install/trust CA on second device; allow real camera/microphone; verify two-way picture/audio. Actual capture and cross-device media remain unverified |
| 6. Group creation | ✅ | Actual Nueva conversación UI: user list, creator plus two selected members, required name, live appearance for B/C. Backend rejects unnamed/under-3 groups and invalid participants; transaction saves chat and participants | `chats2_v.php`, `ChatController.php` | None; private creation remains available with one selected peer |
| 7. Three-person group messaging | ✅ | A→B/C and B→A/C immediately; unrelated D receives nothing; group history survives reload on HTTP and HTTPS | `chats2_v.php`, `server.js`, `ChatModel.php` | Three devices optional; three separate browser profiles also demonstrate it |
| 8. Saved conversations | ✅ | `chats`, `chat_participants`, `messages`; refresh and logout/login tested; all 46 original messages and 19 original accounts retained | `ChatController.php`, `ChatModel.php`, migration | Show phpMyAdmin queries below |
| 9. Encryption toggle | ✅ | Visible checkbox ON/OFF; PHP AES-256-GCM; is_encrypted flag plus v1 ciphertext envelope; real-time/history transparent decryption; DB ciphertext verified; random IV and tamper rejection tested | `chats2_v.php`, `ChatController.php`, `ChatModel.php`, `application/libraries/MessageCipher.php`, `compose.yaml` | Explain storage encryption, not end-to-end encryption |
| 10. Real-time online/offline | ✅ | Socket presence snapshot updates private-chat list/header. Multiple-tab test: closing one socket keeps online, closing last goes offline, reconnect goes online | `server.js`, `chats2_v.php` | “Online” means a connected chat page. An abrupt network loss becomes offline after heartbeat detection, not instantly |
| 11. Offline/pending messages | ✅ | Sender saves while recipient offline; reconnection reloads unread counts automatically; opening conversation loads message; no duplicate rendering | `ChatModel.php`, `ChatController.php`, `server.js`, `chats2_v.php`, `chat_participants.last_read_message_id` | Notification is in the chat interface, not an OS push notification when the application is closed |
| 12. Files inside chat | ✅ | Text, PNG and WAV uploaded via actual chat UI; DB file rows and message links; recipient downloads byte-identical payloads. Browser and backend both reject 21 MB | `FileTransferController.php`, `FileTransferModel.php`, `chats2_v.php` | Optional physical transfer demo; endpoint is the project's HTTP backend API, not Firebase |
| 13. Location link | 🧪 | Browser-granted synthetic coordinates produced a clickable Google Maps URL; live delivery and storage verified | `chats2_v.php`, `messages.message` | Real GPS/location permission and accuracy on device remain manual. Shared chat location is in messages, not necessarily the separate locations module tables |
| 14. Email | 🧪 | Registered-user selector added; backend rejects non-registered address. SMTP verified TLS connection and AUTH status 235 accepted; success logging and safe error response reviewed | `SmtpController.php`, `SmtpModel.php`, `smtp_fifa_v.php`, `compose.yaml` | Send one message to an agreed registered address; confirm inbox/spam. No external email sent during audit because target is not yet supplied |
| 15. Tasks inside group | ✅ | New task selects user's group; assignee list restricted to members; backend rejects outsider; existing Kanban kept. Created task 8 in group 15 assigned to user 27, advanced twice and DB status=goal | `TaskController.php`, `TaskModel.php`, `task_create_v.php`, `task_manager2_v.php`, migration | Show group/assignee labels and completed column; older global tasks remain unchanged with NULL group |
| 16. Rewards | ✅ | Two users view trophies and new shared points ranking. Existing registration/login/referral earning preserved; removed misleading hardcoded count of 18 video calls | `EmployeeModel.php`, `HomeController.php`, `rewards_fifa_v.php`, `EmployeeController.php` | Explain actual earning rules below; no claim that calls currently earn points |
| 17. Other-chat notifications | ✅ | Message to inactive group adds live unread badge; opening group marks read and clears badge; cursors persist in DB | `chats2_v.php`, `server.js`, `ChatController.php`, `ChatModel.php` | Badge is within chat module; no global notification architecture introduced |

Full repeatable browser audit: `scripts/final-audit.cjs`. Recorded output: `docs/final-test-results.txt`. The completed run reports 20 checks passed; additional origin/security checks and encryption/SMTP/DB checks are recorded there.

## Already present, gaps, changes

Already present: Docker/CI3/MariaDB/Socket.IO architecture, private/group messaging and saved history, WebRTC signaling, chat file transfer, Maps-link sharing, email transport, registration/password hashing, Kanban, points/trophy calculations and the Moonio theme. These were inspected and tested as listed, not accepted solely from prior claims.

Missing or incomplete: LAN HTTPS, user-controlled encryption, per-user presence, persisted unread counters, server-side group-size validation, group association for tasks, registered-email selection, shared ranking, duplicate-email rejection, and a visible hang-up button. Registration also had a relative URL redirect bug. These were fixed without changing frameworks. SMTP secrets were moved from the formerly ignored controller into the existing ignored `.env` so a safe controller can be delivered. Socket identity and message/history membership checks now use the existing PHP login session; sender IDs supplied by the browser are not trusted.

## Start and trust LAN HTTPS

Current Mac LAN IP: `192.168.18.14`. Current URLs:

- Mac or second device: `https://192.168.18.14:8443/blog/index.php/login`
- Mac: `https://localhost:8443/blog/index.php/login`
- Existing HTTP remains: `http://192.168.18.14:8080/blog/index.php/login`
- phpMyAdmin on Mac only: `http://localhost:8081`

Docker Desktop must be running. From this repository:

```sh
# The migration has already been applied here. Safe to repeat on MariaDB 10.4.
docker compose exec -T db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" codeigniter' < scripts/migrations/final-delivery.sql

# Already generated for the current IP. Repeat ONLY if the Mac's LAN IP changes.
PATH="/opt/homebrew/bin:$PATH" sh scripts/setup-lan-https.sh 192.168.18.14

docker compose -f compose.yaml -f compose.https.yaml up -d --build
docker compose -f compose.yaml -f compose.https.yaml ps
```

Keep using both Compose files for the HTTPS demonstration. The base Compose file alone still supports HTTP. Never run `down -v` for delivery. The `.certs` directory is ignored and denied through Apache; `.env` is ignored. The optional HTTPS listener proxies Socket.IO polling and WebSocket upgrades through Apache to Node, exactly as HTTP does.

Transfer **only `.certs/moonio-rootCA.crt`** to the second device using AirDrop, USB, or another trusted transfer. Do not transfer `lan-key.pem` or mkcert's `rootCA-key.pem`. mkcert explicitly warns against sharing its CA private key. [mkcert documentation](https://github.com/FiloSottile/mkcert)

- **Mac:** this Mac's Chrome already trusts the CA, verified without bypass flags. If using another Mac, import the public CA in Keychain Access, open its Trust settings, and trust it for SSL. Alternatively, with mkcert installed and the public root in its CA directory, use `mkcert -install`.
- **iPhone/iPad:** AirDrop/open the CA file; install the downloaded profile under Settings → General → VPN & Device Management. Then Settings → General → About → Certificate Trust Settings → enable full trust for this mkcert root. Reopen Safari. Installing the profile alone is insufficient. [Apple's trust instructions](https://support.apple.com/en-us/102390)
- **Android:** copy the CA file; Settings → Security & privacy → More security settings → Encryption & credentials → Install a certificate → CA certificate; select the file and confirm. Menu wording varies by Android/vendor; search Settings for “Install certificate” if needed. Reopen Chrome. [Google certificate instructions](https://support.google.com/pixelphone/answer/2844832?hl=en)
- **Windows:** import the public CA into Trusted Root Certification Authorities for the current user, or run `certutil -user -addstore Root moonio-rootCA.crt` from the folder containing it. Restart Chrome/Edge. [Microsoft certutil reference](https://learn.microsoft.com/en-us/windows-server/administration/windows-commands/certutil)

On both devices open the **HTTPS** URL, with no certificate warning. Allow camera, microphone and location in the site permissions and the OS privacy settings. Do not use an HTTP URL, click through a certificate error, or use security-disabling flags. Both devices must be on a LAN that permits device-to-device traffic (not an isolated guest network). No TURN server has been added: the local direct-media demonstration has not shown a need for one. A physical LAN test is still required.

## Professor demonstration (about 10 minutes)

1. State the actual team members (maximum three). Open phpMyAdmin on the Mac, and the HTTPS app in independent browser profiles/devices as A/B/C. Use existing accounts, or Login → Registrar. Photo is optional; email is unique; password requires at least 8 characters with upper/lowercase, number and symbol.
2. A opens Chats → Nueva conversación → select B only → create. A and B open that private conversation. Send one message each without refreshing. C must not see that private conversation.
3. A creates a second conversation: select B and C and enter “Entrega final”. All three see it automatically. Send A→B/C and B→A/C. Refresh C and reopen it to show history.
4. A opens the private conversation while B sends to the group. Show the unread badge; open the group and show it clear.
5. Close **all B chat tabs**. A sees B offline. A sends a private message. Reopen/login B, open Chats, show pending badge and then the saved message. Temporarily interrupt B's network and restore it to show automatic reconnect/history.
6. In private chat, send “normal demo” with Cifrado unchecked; then “secret demo” with it checked. Both are readable to B; phpMyAdmin shows the second as `v1:...` with `is_encrypted=1`, not readable text.
7. A clicks Videollamada; B accepts and grants camera/mic. Confirm both videos and audio; use headphones to avoid echo. Click **Colgar**; camera indicators should stop. This is the required physical acceptance check.
8. Send a small PNG, TXT and audio file using Archivo; download on B. Click Ubicación, allow permission, and open the received Maps link. Verify the location is appropriate for the real device.
9. Tasks → Nueva Tarea → select “Entrega final” → select B → create. Show group/member labels, advance Pending → In Progress → Completed. Refresh and show DB `status='goal'`.
10. Open Recompensas on A and B; show shared ranking and trophy rules. Email → choose B's registered email → enter a clearly labeled delivery-test subject/body → send → confirm success and B's inbox/spam. A successful SMTP submission is not proof of inbox delivery; inspect the inbox.

## phpMyAdmin evidence

Database: **codeigniter**. No original records were deleted. Labeled `Final audit` chats/tasks and `Finalaudit` demo users were added by tests and deliberately retained. Their random credentials are local-only in `/tmp/moonio-final-fixtures.json` (not in Git); use your own accounts for the demonstration.

| Table | Columns to show | Meaning |
|---|---|---|
| `employee` | `id,email,first_name,last_name,points` | Email-identified accounts and points. Passwords are hashes; do not display credentials |
| `chats` | `id_chats,type,name,group_image` | Private/group identity |
| `chat_participants` | `id_chats,id_employee,last_read_message_id` | Membership and durable unread cursor |
| `messages` | `id_messages,id_chats,id_employee,message,is_encrypted,created_at` | Persistence, sender, normal vs ciphertext |
| `ftp_files` | `id_file,file_name,file_path,file_size,uploaded_by` | Backend-upload metadata; bytes live in uploads/ |
| `tasks` | `id_task,id_chats,title,assigned_to,created_by,status` | Group task, assignee and completed state |
| `email_logs` | `sent_to,subject,sent_by,created_at` | Successful SMTP submissions after the manual send |
| `reward_types` | `id_reward_type,reward_type,points` | Configured reward types |
| `rewards` | `id_employee,id_reward_type,points` | Actual earning ledger |
| `trophies` | `trophy_name,requirement_type,requirement_value` | Threshold definitions; unlocks calculated from points/message count |

Evidence from the successful automated run: **group 15** has **3 participants** (26/27/28); **private chat 16** has **2** (26/27); **task 8** belongs to group 15, assignee 27, `status=goal`. Message **93** contains encrypted storage; messages 91/92 contain normal text. User 29 is the excluded fourth user. If the suite is rerun it creates additional labeled conversations; use the IDs from its latest output.

```sql
SELECT id_chats, type, name FROM chats WHERE id_chats IN (15,16);
SELECT id_chats, id_employee, last_read_message_id
FROM chat_participants WHERE id_chats IN (15,16);
SELECT id_messages, id_chats, id_employee, is_encrypted, message, created_at
FROM messages WHERE id_chats IN (15,16) ORDER BY id_messages;
SELECT id_task, id_chats, assigned_to, status FROM tasks WHERE id_task=8;
SELECT * FROM ftp_files WHERE uploaded_by=26;
SELECT reward_type, points FROM reward_types;
SELECT id_employee, id_reward_type, points FROM rewards WHERE id_employee IN (26,27);
-- Preservation checks at audit completion:
SELECT COUNT(*) AS original_messages FROM messages WHERE id_messages<=61; -- 46
SELECT COUNT(*) AS original_accounts FROM employee WHERE id<25; -- 19
```

## Architecture explanation

Browser → CodeIgniter/PHP → MariaDB handles pages, login/registration, chat lists/history, groups, uploads/downloads, tasks, rewards and email. `fetch()` requests are ordinary HTTP(S) requests to these endpoints.

Browser ↔ Apache `/socket.io/` proxy ↔ Node.js/Socket.IO handles live messages, presence, chat-list refresh hints and WebRTC signaling. The browser connects automatically to its own origin; after the initial handshake, tests verified a WebSocket transport, including over HTTPS. Socket.IO can fall back to polling.

Node → PHP `api/socket_session` validates the existing session cookie. `register_user` associates the validated employee with their socket(s) and user notification room; it does not trust a posted employee ID. `join_room('chat_N')` rechecks membership through PHP before joining. On reconnect the client registers and rejoins again.

`send_message` sends text, chat ID and the encryption choice to Node. Node forwards the existing session cookie in an HTTP POST to PHP `api/save_message`. PHP verifies membership, optionally encrypts the text and inserts into MariaDB. **Only after a successful insert** does Node emit `receive_message` to the participant user rooms and acknowledge the sender. Participant user rooms allow notifications even when another conversation is open. Recipients deduplicate by persisted `id_messages`.

`fetch(api/messages/N)` retrieves authorized, decrypted history. `fetch(api/user_chats/ID)` returns authorized conversations and unread counts. `api/mark_read/N` advances that member's cursor when displayed messages are acknowledged. `presence` is pushed when the registered socket set changes; no polling was added.

WebRTC: browser ↔ browser transports audio/video media. Socket.IO carries `call_user` → `incoming_call`, `call_answer`, `call_ice_candidate`, and `end_call`; it does not carry the video frames. `getUserMedia` obtains local media; SDP offer/answer and ICE establish RTCPeerConnection; local/remote video elements display streams; hang-up closes the peer connection and stops local tracks. Trusted HTTPS enables browser camera/location APIs on a second device. The synthetic-media test exercised the real connection/signaling code, but it is not a physical camera or cross-device network test.

Files: multipart HTTP POST to the project's PHP upload API stores file metadata in MariaDB and bytes in `uploads/`; a Socket.IO message shares a download endpoint link. The recipient uses HTTP GET to download. This is a server/API transfer implementation (“Firebase Storage **or another API**”), so no Firebase migration is needed. It does not claim third-party cloud storage, attachment encryption, or per-conversation file ACLs.

Email: PHP connects to the existing Gmail SMTP service over TLS and logs successful submissions. SMTP credentials stay in `.env`/container environment, not client JavaScript or committed PHP.

## Encryption guarantees and limits

- Per-message choice; unchecked sends normal storage, checked sends encrypted storage. Existing rows default to `is_encrypted=0` and remain readable.
- PHP encrypts with AES-256-GCM, fresh 12-byte IV, 16-byte authentication tag, and a random 32-byte server key from `MESSAGE_ENCRYPTION_KEY`. Storage format is `v1:` plus base64(IV + tag + ciphertext).
- PHP decrypts authorized history; live plaintext is emitted to participants after storage succeeds. The 🔒 marker identifies encrypted storage.
- Protects message bodies in a database-only disclosure when the key is absent, and detects ciphertext tampering. HTTPS protects browser/server transit when the trusted HTTPS origin is used.
- **Not end-to-end encryption:** PHP/Node and anyone holding the server key can read message text. Sender/recipient/chat IDs, timestamps and file bytes are not encrypted by this option. Plaintext is present in the browsers and server memory; HTTP mode lacks HTTPS transit protection.
- Keep the ignored `.env` backed up privately. Do not regenerate/delete the key after saving encrypted messages. There is no key rotation/recovery feature. If the key is absent, encrypted sends fail rather than silently falling back to plaintext.

## Rewards explanation

Actual code awards **10 points for successful registration**, **5 for each successful login**, and **25 to a valid referrer after successful registration**. `rewards` records events; `employee.points` stores the total. Repeated logins currently earn points (no daily cap). The DB also defines chat/group/call reward types, but no active awarding code was found for those types, so do not promise points for calls or messages. Message count does unlock matching message-threshold trophies.

Divisions use current EmployeeModel thresholds: 0 / 50 / 150 / 300 / 600 / 1000 / 2000 points. Trophies are calculated from current points/message totals and `trophies.requirement_*`; `employee_trophies` is not the source of truth in the current views. Every logged-in user can view their trophies and the shared ranking. The previous fixed “18 video conferences” display was removed because it was not an actual counter.

## Additive changes, tests and limits

Migration `scripts/migrations/final-delivery.sql` adds only `messages.is_encrypted`, `chat_participants.last_read_message_id`, and nullable `tasks.id_chats`. It is idempotent on the project's MariaDB version. Old tasks remain valid. Preserve the database volume and uploads. A new environment needs the migration and a stable base64-encoded 32-byte encryption key; `scripts/create-docker-env.py` now generates one for new `.env` files and still refuses to overwrite an existing `.env`.

Run automated browser tests using an available Playwright installation and Chrome:

```sh
PLAYWRIGHT_PATH=/tmp/muro_formato_20261119/herramientas/node_modules/playwright \
  node scripts/final-audit.cjs
```

`PLAYWRIGHT_PATH` is local test tooling, not a runtime dependency. On another machine install Playwright separately and set its module path; `CHROME_PATH` can override Chrome's executable. No insecure HTTPS setting or security-disabling Chrome flag is used. The test deliberately injects generated media into test pages for signaling checks and supplies synthetic geolocation, so actual camera/audio/GPS are still manual. The suite adds labeled data; it does not delete existing data or send external email.

Remaining manual acceptance: actual team roster, second-device certificate trust and real two-way camera/audio, real geolocation, and inbox delivery to the chosen registered email. SMTP authentication already passed. No promise of production security hardening is made for unrelated legacy employee/file/task endpoints.

## Files and Git scope

`docs/final-git-review.txt` records the exact staged file list/diff summary and pre-commit status. Changes cover the chat controller/model/view/server, MessageCipher, registration validation, SMTP controller/view, group task controller/model/views, shared rewards view, optional HTTPS infrastructure, additive SQL, environment generator/example, and regression scripts/docs. The existing task edit view and four chat icon assets are included as dependencies of the tested views, without redesign. Private `.env` and `.certs` changes are local-only. Unrelated pending theme/blog/EmployeeModel changes and old-view deletions were left untouched and outside this commit. The final chat handoff supplies the actual commit/push result and post-commit status.
