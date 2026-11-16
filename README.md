# ResQNet Backend

Laravel 11 backend for ResQNet, an animal rescue and adoption platform. It serves:

- a **REST API** (`/api/v1`, Sanctum tokens) for the React Native mobile app: sign-up with email OTP, pets, adoptions, rescue reports, donations, notifications;
- the **Admin dashboard** (`/admin`, role `super_admin`): organizations, users, pets, vets, health records, donations, analytics;
- the **Organization Center** (`/organization`, role `organization`): pets, adoptions, rescue cases and team dispatch, health records, vets, finance, profile;
- a public donation page (`/donation`, Khalti) and public pet pages by QR code.

Stack: PHP 8.3, MySQL 8, Laravel 11, Sanctum, spatie/laravel-permission, spatie/laravel-medialibrary, Laravel Reverb (realtime), FCM push notifications, Khalti payments, Vite + Tailwind + Alpine.

## Local setup

Requirements: PHP 8.3 with `intl`, `pdo_mysql`, `gd`; Composer 2; Node 20+; MySQL 8.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a local database and user, put them in `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`), then:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build        # or `npm run dev` while working on the dashboards
php artisan serve
```

In `local`/`testing` the seeder creates a super admin (`ADMIN_EMAIL` / `ADMIN_PASSWORD`, default password `12345678` locally) and demo organizations, users and pets. Outside `local`/`testing` it refuses to run without `ADMIN_PASSWORD` and skips demo data.

`composer dev` starts the web server, a queue listener, log tailing and Vite together.

### Uploads with `php artisan serve`

Pet images and registration documents may be up to 5 MB. `public/.user.ini` raises PHP's limits on PHP-FPM hosts, but the built-in server ignores it, so locally set `upload_max_filesize = 6M` and `post_max_size = 32M` in your CLI `php.ini` (`php --ini` shows where it is).

### Testing on a phone (ngrok / LAN)

Set `DYNAMIC_APP_URL=true` so links, QR codes and image URLs use the incoming host. Keep it `false` anywhere else.

## Environment variables

`.env.example` lists every variable the code reads, with placeholders only. The important ones:

| Variable | Purpose |
| --- | --- |
| `APP_KEY` | Encryption key. Generate locally; set separately per environment. See [docs/SECURITY.md](docs/SECURITY.md) before rotating. |
| `APP_URL`, `FORCE_APP_URL` | Public base URL. In production set `FORCE_APP_URL=true` so links never use a forwarded host. |
| `DB_*` / `DB_URL` / `JAWSDB_URL` | Database. On Heroku the JawsDB add-on sets `JAWSDB_URL`, which is read automatically. |
| `ADMIN_NAME`, `ADMIN_EMAIL`, `ADMIN_PASSWORD` | Super admin created by `db:seed`. |
| `QUEUE_CONNECTION` | `database` in production (needs a worker), `sync` is fine locally. |
| `MAIL_*` | SMTP for OTP codes, organization status emails and donation thank-you emails. |
| `BROADCAST_CONNECTION`, `REVERB_*`, `VITE_REVERB_*` | Realtime dashboard updates. `log` disables them. |
| `FCM_CREDENTIALS_PATH` | Firebase service-account JSON for push notifications. Never commit the file. |
| `KHALTI_SECRET_KEY`, `KHALTI_SANDBOX`, `KHALTI_WEBSITE_URL` | Khalti payments. Keep `KHALTI_SANDBOX=true` outside production. |
| `SANCTUM_TOKEN_EXPIRATION` | Mobile token lifetime in minutes (default 30 days). |
| `SESSION_SECURE_COOKIE` | `true` in production (HTTPS). |
| `CORS_ALLOWED_ORIGINS` | Allowed origins for `/api` (default `*`, the API uses bearer tokens only). |
| `MEDIA_DISK` | Disk for pet images and logos (default `public`). Registration documents always use the private disk. |

## Background services

| Service | Command | Needed for |
| --- | --- | --- |
| Queue worker | `php artisan queue:work --tries=3` | Queued mails and notifications (organization status, adoption updates, pet suspension) when `QUEUE_CONNECTION` is not `sync` |
| Scheduler | `php artisan schedule:run` every minute (cron), or `php artisan schedule:work` | Daily notification cleanup and expired-token pruning |
| Reverb | `php artisan reverb:start` | Realtime dashboard updates when `BROADCAST_CONNECTION=reverb` |

## Tests and checks

The suite runs against a separate MySQL database named `resqnet_testing` (see `phpunit.xml`), which it wipes. Create it once with the same credentials as your local database.

```bash
php artisan test
php artisan route:list --except-vendor
composer audit
```

Khalti, FCM, SMTP and Reverb are faked in tests; real payments, pushes, emails and broadcasts need a manual check in a sandbox.

## Production deployment

CircleCI (`.circleci/config.yml`) runs the tests on every push and deploys `main` to Heroku. Production needs:

1. Config vars: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY`, `APP_URL`, `FORCE_APP_URL=true`, `SESSION_SECURE_COOKIE=true`, mail, Khalti, Reverb and FCM settings. The database comes from `JAWSDB_URL`.
2. Migrations after each deploy: `php artisan migrate --force`.
3. A queue worker and the scheduler (Heroku: a `worker` process and the Scheduler add-on), and a Reverb server if realtime updates are wanted.
4. `php artisan storage:link`, and persistent storage for uploads: Heroku's filesystem is wiped on every restart, so use S3 (`MEDIA_DISK=s3` plus `AWS_*`) there.
5. `php artisan config:cache route:cache view:cache` on boot.
6. After deploying the private registration documents change, run `php artisan resqnet:secure-registration-documents` once, check the admin Organizations page, then run it again with `--delete-public`.

## Security notes

See [docs/SECURITY.md](docs/SECURITY.md) for where secrets live and how to rotate `APP_KEY` and database credentials. Security-relevant behaviour:

- Login, OTP and password-reset routes are rate limited per IP; accounts lock for a minute after 5 failed logins.
- OTP codes come from a secure generator and are invalidated after 5 wrong guesses.
- Organizations can only see and change their own pets, health records, rescue cases, team members and vets. Suspending an organization deactivates all of its accounts.
- Organization registration documents are private and only downloadable by super admins.
- Mobile users only see their own rescue reports, adoptions and donations. Push tokens are never serialized.
- Khalti payments complete only when the gateway confirms the exact amount, and completion is idempotent.
