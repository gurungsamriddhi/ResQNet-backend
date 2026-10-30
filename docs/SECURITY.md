# Secrets and key management

## Where secrets live

| Secret | Local development | Production (Heroku) |
| --- | --- | --- |
| `APP_KEY` | `.env` (generate with `php artisan key:generate`) | Heroku config var `APP_KEY` |
| Database credentials | `.env` `DB_*` values | `JAWSDB_URL`, set by the JawsDB add-on and read by `config/database.php` |
| Khalti, Reverb, mail, FCM | `.env` | Heroku config vars |
| FCM service account JSON | `storage/app/firebase-auth.json` (git-ignored) | Path set via `FCM_CREDENTIALS_PATH` / `FIREBASE_CREDENTIALS` |

Never commit `.env`, connection strings, keys or terminal output from `heroku config`.
`.env.example` holds placeholders only. Tests and CI generate their own `APP_KEY`;
no key is hard-coded in `phpunit.xml` or `.circleci/config.yml`.

## Rotating the production `APP_KEY`

ResQNet stores no encrypted database columns, so a new key only affects
encrypted cookies (sessions, remember-me) and signed URLs (rescue team magic
links). Laravel accepts old keys listed in `APP_PREVIOUS_KEYS`, so rotation does
not log anyone out.

1. Generate a key without writing any file:
   ```bash
   php artisan key:generate --show
   ```
2. Set the new key and keep the current one as a previous key, in one command
   (this restarts the dyno once):
   ```bash
   heroku config:set -a <app> APP_KEY='<new key>' APP_PREVIOUS_KEYS='<current key>'
   ```
3. Check admin and organization login and one rescue magic link.
4. After 30 days, drop the old key:
   ```bash
   heroku config:unset -a <app> APP_PREVIOUS_KEYS
   ```

Do not run `php artisan key:generate` on the production server: it rewrites
`.env` without keeping the previous key.

## Rotating database credentials

Reset the password from the JawsDB dashboard (`heroku addons:open jawsdb -a <app>`).
Heroku updates `JAWSDB_URL` automatically and the app picks it up on restart.
If the add-on offers no reset, provision a new JawsDB instance, copy the data
with `mysqldump`, switch the app over, and remove the old instance only after
verifying the new one.
