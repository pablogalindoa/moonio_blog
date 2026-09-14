# Legacy Docker development environment

This environment keeps CodeIgniter 3.1.13, PHP 8.2/Apache, MariaDB 10.4.28 and the existing Node/Socket.IO server. XAMPP remains available separately. No React or TURN service is included.

## Preparation

Use Docker Desktop with Compose v2. On this Mac, add `/Applications/XAMPP/xamppfiles/htdocs/blog` under Settings → Resources → File Sharing. Docker cannot bind this non-default path until it is shared. See [Docker Desktop settings](https://docs.docker.com/desktop/settings-and-maintenance/settings/).

Keep the private verified `application/database/current_codeigniter.sql`, existing uploads tree, and the three ignored credential-bearing controllers locally. These are deliberately absent from Git and image build contexts. A new clone needs those private artifacts from the existing backups before it reproduces the whole application.

Run `python3 scripts/create-docker-env.py` once. It creates an ignored mode-0600 `.env` with random database credentials and the host user's UID/GID; it refuses to overwrite an existing file. `.env.example` documents the settings. If changing the web port, update APP_BASE_URL and CHAT_ALLOWED_ORIGIN too. Rebuild web after changing UID/GID.

## Commands

```sh
# Build and start
docker compose build
docker compose up -d --wait --wait-timeout 180

# Stop without removing containers or data
docker compose stop

# Restart existing containers
docker compose restart

# Apply configuration changes
docker compose up -d --build --wait

# Status and logs
docker compose ps
docker compose logs --tail=100
docker compose logs -f web db chat phpmyadmin

# Shells
docker compose exec web bash
docker compose exec db sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" exec mysql --user="$MYSQL_USER" codeigniter'
docker compose exec chat sh

# Remove only this stack's containers/network; named volumes survive
docker compose down
```

Do not use `down -v` for normal operation. For a **deliberate destructive reset of only the Docker database**, first export any wanted Docker data, then run:

```sh
docker compose down
docker volume rm moonio-dev_db_data
docker compose up -d --wait --wait-timeout 180
```

These reset commands are documentation only; they are not part of the normal workflow. Never remove XAMPP database files or import the historical multi-database dump.

## URLs and networking

- Login: http://localhost:8080/blog/index.php/login
- Application prefix: http://localhost:8080/blog/
- phpMyAdmin: http://localhost:8081/ (application database user only)
- Socket.IO: http://localhost:8080/socket.io/ (Engine.IO endpoint, not a webpage)

Web and phpMyAdmin bind only to 127.0.0.1. Neither database nor chat publishes a host port. The unrelated host process on port 3000 remains untouched. Apache forwards polling and WebSocket upgrades at `/socket.io/` to `chat:3000`; browser code uses the public page origin. Chat persists through `http://web/blog/index.php/api/save_message`. Docker DNS names appear only in server-side configuration.

## Data and writable paths

On the first empty-volume startup, the DB wrapper copies the read-only verified export into a container tmpfs initialization directory so MariaDB can read the host's protected mode-0600 file. It never changes the source export's permissions. Only that application export is imported; `uploads/all_databases.sql` is never an initialization source.

The named `moonio-dev_db_data` volume preserves the database across down/up and image rebuilds. An existing database skips initialization. Changing `.env` credentials does not alter users/passwords already stored in that volume.

The application is mounted read-only, with the original `uploads/` mounted separately read/write. Uploads—including `Videos/`—retain their original paths and permissions. Apache workers use the host UID/GID, avoiding recursive chmod/chown. Application writes to uploads are therefore shared with XAMPP; avoid concurrent editing/testing in both environments. Keep the verified Phase 1.6 external archive and take another archive before later migration steps. No original media is copied, renamed or repaired by this setup.

Sessions, CI logs and CI cache use separate named volumes; temporary uploads use tmpfs. PHP upload/post limits are 40M, memory 512M, execution/input timeouts 120/60 seconds, timezone Europe/Berlin. MariaDB uses America/Monterrey. Apache denies access to application/system, hidden files, SQL backups and infrastructure directories; uploaded PHP cannot execute.

## Scope and rollback

Environment-aware PHP and chat configuration retain the previous XAMPP defaults when Docker variables are absent. Routing keeps `index.php`. The existing invalid default route (`fronted/login`) is not repaired; use the explicit login URL. Existing module bugs, three missing media files and WebRTC lifecycle issues remain for later phases. Localhost development uses HTTP; remote camera/microphone use would require a secure public origin and is outside Phase 2.

Stop Docker with `docker compose stop` to return to the existing XAMPP workflow. No source database directory is mounted in Docker. Database changes in Docker do not synchronize to XAMPP.
