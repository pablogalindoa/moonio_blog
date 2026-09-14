# Phase 1.6: pre-Docker safety baseline

Completed 2026-09-14. This phase does not change application behavior and does not implement the Docker migration.

## Git baseline and private files

Git was initialized on branch `main`. The first commit is titled `Baseline before Docker migration`. Its exact file list is `phase16-first-commit-files.txt` in this directory. Package manifests, lockfiles, CodeIgniter system/application source, templates, static assets and preparation documentation are included, subject to the exclusions below.

No credential-bearing source was refactored or rotated. These whole files are intentionally excluded from Git:

- `application/controllers/SmtpController.php`: embedded SMTP credential, line 33.
- `application/controllers/ExternalApi.php`: embedded API key, line 6.
- `application/controllers/PageController.php`: password/hash fixture, lines 12–13.

Consequently, the Git commit alone is not a standalone runnable backup. The private companion archive below preserves these files unchanged. A later credential-externalization change can safely add these source files to Git. Until then, do not force-add them.

The `.gitignore` also excludes all node_modules directories, Composer vendor output, real environment files and private key material, the entire private application/database directory, uploads, database dumps, local archives, prospective database-volume directories, runtime logs/cache, generated frontend/test/documentation output, OS metadata and editor state. Framework cache/log index.html scaffolding remains tracked. Safe `.env.example` and `.env.sample` names are allowed, but their contents must still be inspected before adding them. Package lockfiles remain versioned. No ignored file was deleted.

## Isolated restore validation

The original `application/database/current_codeigniter.sql` was imported unchanged into a new `mariadb:10.4.28` container. Exact source-series version was available, so no version fallback was needed. Restored server: `10.4.28-MariaDB-1:10.4.28+maria~ubu2004`.

Isolation controls:

- Temporary container `blog-phase16-restore-20260914`, labelled for this test only.
- `--network none`, no published host ports, and `--skip-networking`.
- No host bind mounts; `/var/lib/mysql` was tmpfs, not XAMPP storage.
- The application database did not exist before import.
- The test never connected to the source XAMPP database. Live-baseline comparisons use Phase 1.5's previously verified live counts.
- Container-local root used an empty password only inside this disconnected disposable instance; this is not the future deployment configuration.

Results:

- 24 restored tables; 244 rows.
- Every table count matches both the SQL export and the saved live-XAMPP baseline.
- All 24 CREATE TABLE statements match exactly, covering 132 columns, indexes, defaults, AUTO_INCREMENT options and foreign keys.
- All 24 next AUTO_INCREMENT values match; empty auto-increment tables correctly start at 1.
- All eight declared foreign keys match; no orphan references; foreign_key_checks is enabled.
- All application tables use InnoDB with utf8mb4_general_ci; database default is utf8mb4 / utf8mb4_general_ci.
- A four-byte UTF-8 trophy character retains its exact bytes and has character length one. An accent-insensitive collation comparison passed.
- Task status enum is bench/training/goal, default bench. Priority enum is friendly/regular/decisive, default regular.
- The reward/task/location/file/email additions are present and their full DDL matches.
- 17 representative SELECT checks passed: employee lookup, reward types/history, trophies, task board, blogs, approvals, message history, private/group chats, file metadata, email logs, location lookup, World Cups, champions aggregation, comments and likes.
- Re-dumping the restored database reproduced every original INSERT statement exactly: row contents, not only counts, match.
- No SQL compatibility problem was discovered, and the original export was not edited.

Private machine-readable evidence is in `application/database/phase16-restore-verification.json`, excluded from Git. Known application-level bugs were not changed and are not disproved by successful schema/read-query validation.

| Table | Saved live baseline | SQL export | Restored |
|---|---:|---:|---:|
| blog_categories | 8 | 8 | 8 |
| blogs | 13 | 13 | 13 |
| blogs_admin | 15 | 15 | 15 |
| categories | 8 | 8 | 8 |
| chat_participants | 20 | 20 | 20 |
| chats | 5 | 5 | 5 |
| comments | 3 | 3 | 3 |
| countries | 20 | 20 | 20 |
| email_logs | 2 | 2 | 2 |
| employee | 19 | 19 | 19 |
| employee_trophies | 0 | 0 | 0 |
| ftp_files | 3 | 3 | 3 |
| likes_admin | 8 | 8 | 8 |
| locations | 0 | 0 | 0 |
| messages | 9 | 9 | 9 |
| mundiales | 29 | 29 | 29 |
| request_types | 3 | 3 | 3 |
| requests | 15 | 15 | 15 |
| reward_types | 6 | 6 | 6 |
| rewards | 9 | 9 | 9 |
| tasks | 7 | 7 | 7 |
| trophies | 18 | 18 | 18 |
| user_locations | 2 | 2 | 2 |
| worldcup_champions | 22 | 22 | 22 |
| **Total** | **244** | **244** | **244** |

Cleanup: the test container was stopped and automatically removed with its tmpfs database. No test volume remains. The downloaded MariaDB image remains in Docker's cache. Other containers/volumes were not removed; the main application stack was not created.

## Upload and private-source backup

Backup directory: `~/Backups/blog-phase16-20260914/`, outside the Apache document root, mode 0700. Files are mode 0600. These are private local archives, not encrypted off-site backups.

| Archive | Bytes | Contents |
|---|---:|---|
| uploads.tar.gz | 297176350 | All 165 existing files under uploads, 301082113 uncompressed bytes |
| private-baseline.tar.gz | 47456 | Three excluded source files and the six pre-existing application/database artifacts |

SHA-256:

```text
951936f3a1deae4b5a65a00feb6604198ebbd69df6cdea9746bae73fbb4a6fd5  uploads.tar.gz
8ca432eb4d8d92c1a8c12694654c142578902fd92e0d8e0be375c342b1d93994  private-baseline.tar.gz
```

The backup directory also contains `backup-manifest.json` with per-file hashes and `SHA256SUMS`. Both archives were reopened and every regular member's content hash and timestamp checked against its source. The format is gzip-compressed POSIX PAX tar: exact filenames (including Unicode and case), structure, file modes and modification times are preserved. macOS-specific ACLs and extended attributes are not promised by this archive format.

The full uploads tree includes users, Videos (uppercase V), blogs, admin_posts, background, flags, groups, worldcups and root-level files. The historical uploads/all_databases.sql is intentionally retained in this private archive; it must not be used for application restore or later served publicly. The three missing database-referenced media assets remain missing; nothing was synthesized or repaired.

Before migration/cutover:

1. Keep this dated backup immutable. Do not replace it with a newer archive.
2. Verify its SHA256SUMS before any recovery. Archive content was validated without overwriting or moving original files.
3. If the application receives new writes, schedule a short write freeze and capture a new application-only SQL snapshot plus a new dated upload archive. This archive and the Phase 1.5 SQL are not claimed to be an atomic cross-database/filesystem snapshot.
4. Retain a second verified copy on separate storage under the owner's control before destructive operations; no off-machine copy has been made in this phase.
5. Restore archives only into a new staging directory first. Check their manifest hashes before copying media into any future persistent volume. Preserve Unicode and case; do not flatten directories or overwrite the source.
6. The private companion archive plus Git baseline plus uploads archive form the recovery set. Keep the live XAMPP data untouched until the future environment is independently accepted.

## Phase 2 port decision

The unrelated `node servidor.js` process in `~/Downloads/gcw52` owns host port 3000 and was not stopped, killed or modified.

Preferred future design: keep chat listening on container-internal port 3000, publish no host port for it, and proxy `/socket.io/` through the application's Apache origin. Browser JavaScript uses the public application origin and Socket.IO path; it must never use an internal Compose service hostname. Apache may use `chat:3000` internally. Preserve both polling and WebSocket upgrades.

If direct development access is temporarily needed, use host port 3001 mapped to internal 3000 only after checking 3001 is available. No host-port mapping or application URL was changed in this phase. Trusted HTTPS remains necessary for remote-device camera/microphone access.

## Scope of project changes

- Modified `.gitignore` only among pre-existing project files.
- Created this document and the exact first-commit file manifest.
- Created private `application/database/phase16-restore-verification.json` (ignored).
- Created `.git` metadata and the baseline commit.
- Created external backup archives/manifests and temporary verification scripts outside the project.
- No application PHP/JavaScript/configuration/media or original SQL export was changed.
- No Dockerfile, Compose file, application container stack, or application behavior migration was implemented.
