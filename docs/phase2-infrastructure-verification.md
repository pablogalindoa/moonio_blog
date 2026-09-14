# Phase 2 infrastructure verification

Baseline: `df6f1646ff19ccd32a36c6324239bf00763ca043` on main; initial working tree clean. Work isolated on `docker-migration`. The new origin was checked with `git ls-remote --symref origin` and was empty before main was pushed.

Runtime actually tested: PHP 8.2.33, Apache 2.4.68, MariaDB 10.4.28, Node 22.14.0 and phpMyAdmin 5.2.2. MariaDB uses the exact 10.4.28 amd64 image under Docker Desktop emulation. PHP retains the requested 8.2 minor version, with a newer patch than XAMPP's 8.2.4.

## Results

- Web/chat builds passed; all four service health checks passed.
- Apache executed PHP; CI login returned HTTP 200 and the users API returned 19 users without printing private response data.
- The fresh Docker DB contained 24 tables and 244 rows. Every per-table count matched the private Phase 1.5 verification.json.
- Every SHOW CREATE TABLE result matched the export exactly, covering all 132 columns, enums, indexes, 8 foreign keys, AUTO_INCREMENT values and charset/collation declarations.
- phpMyAdmin returned HTTP 200 and displayed the codeigniter table listing using application-specific credentials.
- Apache Socket.IO polling returned an Engine.IO opening packet; WebSocket returned HTTP 101 and completed Socket.IO namespace connection.
- Chat reached the internal PHP persistence endpoint. An empty POST returned the expected "No data received" response; no application records were inserted.
- Apache worker can write sessions, temporary uploads, CI logs/cache and existing uploads/Videos using the matching host UID/GID. No chmod/chown was performed on host uploads.
- Requests for .env, .git/config, application config, system source and the historical uploads SQL dump returned HTTP 403.
- A normal compose down/up preserved the named database volume and the same baseline schema/counts; all services became healthy again. No volume-removal command was run.
- Original uploads, SQL export and three private controllers (169 files in total) matched their prior SHA-256 hashes and permission modes.
- Unrelated host Node PID 22401 continued listening on 3000. Docker publishes only loopback 8080 and 8081.

## Environment issues resolved

Docker Desktop initially could not bind the project under /Applications. Its file-sharing list was extended only with this project path, retaining standard shares. Docker Desktop was restarted when it had no running containers. A pre-change settings backup is at `/private/tmp/moonio-docker-settings-before-sharing.json`; this is a temporary host settings backup, not the persistent media archive.

The phpMyAdmin image disables allow_url_fopen. Its health check enables that setting only for the checking CLI process; the application's PHP configuration is unchanged.

## Known limits

`/blog/` still returns the pre-existing default-route 404; use `/blog/index.php/login`. phpMyAdmin logs the image's harmless missing-ServerName notice. Deliberate denied-path probes produce expected Apache authorization errors. No full login workflow, module parity, chat message insertion or WebRTC call was tested in this phase.

Case-sensitive Linux deployment remains a Phase 3 portability concern: current routes use `Frontend` while the directory is `frontend`; the existing macOS bind mount serves the tested login successfully. No route normalization was made. Database lower_case_table_names is 0 in Linux instead of XAMPP's 2; exported table names are lowercase and the infrastructure checks pass.

Private artifacts remain ignored, and existing hardcoded credentials were neither edited nor rotated. Local Docker credentials exist only in ignored .env. The Phase 1.6 upload archive remains the rollback backup; this phase does not replace or alter it.
