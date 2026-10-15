# Online Store

Laravel ecommerce storefront with admin, vendor, and customer apps.

## Requirements

- PHP 8.2+
- MySQL
- Composer
- Node.js 18+

## XAMPP (Windows)

1. Copy this project to `C:\xampp\htdocs\online-store`.
2. Copy `.env.example` to `.env`.
3. In `.env` set `DB_DATABASE=online_store`, `DB_USERNAME=root`, and `DB_PASSWORD=` (or your MySQL password).
4. Create an empty MySQL database named `online_store`.
5. Import `installation/backup/database.sql` in phpMyAdmin. Do not migrate on an empty database.
6. From the project folder:

```bash
php artisan key:generate
php artisan storage:link
php artisan optimize:clear
```

7. Open `http://localhost/online-store`.

PHP dependencies are included in `vendor/`. If `vendor/autoload.php` is missing, run `php composer.phar install --ignore-platform-reqs`.

### XAMPP 500 ("Oops! An Error Occurred")

That Symfony page means Laravel crashed with `APP_DEBUG` off. The real error is in `storage/logs/laravel.log`.

Most common causes after a clone:

1. Missing `.env` or empty `APP_KEY`
2. MySQL database not created / `database.sql` not imported
3. Wrong `DB_PASSWORD` in `.env` (XAMPP root is usually empty)
4. PHP older than 8.2 (XAMPP 7.x / 8.0 / 8.1 will 500)

Fix:

```bash
copy .env.example .env
php artisan key:generate
php artisan optimize:clear
```

Confirm `.env` has:

```
APP_DEBUG=true
APP_URL=http://localhost/online-store
DB_DATABASE=online_store
DB_USERNAME=root
DB_PASSWORD=
```

Then import `installation/backup/database.sql` in phpMyAdmin and reload. If it still 500s, open `storage/logs/laravel.log` and use the last exception.

## Setup

The application ships with a single-page browser installer. Point the web
server document root at `public/` (or the project root for the XAMPP layout),
then open `/install` in the browser.

The wizard collects everything in one form:

- Application URL
- Database host, port, name, username, password
- Business name and currency model
- Super admin name, email, phone, password

On submit it writes `.env`, imports `installation/backup/database.sql`, creates
the super admin, links storage and clears caches. No terminal step is required.

A fresh clone is treated as "not installed" (`APP_INSTALL=false` in
`.env.example`), so every request is redirected to `/install` until setup
finishes. The installer writes `APP_INSTALL=true`, after which the storefront
and admin panel become available. To re-run the installer, set
`APP_INSTALL=false` in `.env` and visit `/install` again.

### Manual setup (alternative)

```bash
cp .env.example .env
php artisan key:generate
```

Import `installation/backup/database.sql` before running migrations. Do not migrate on an empty database.

```bash
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

Set `APP_INSTALL=true` in `.env` once done.

Frontend (Builder storefront):

```bash
npm install
npm run build
```

## Useful commands

```bash
php artisan serve
npm run dev
php artisan optimize:clear
```

## Notes

- Configure payment, mail, SMS, and Firebase keys from the admin panel. Do not commit secrets.
- License phone-home, installer-only routing, ZIP self-update extraction, and demo database wipe are disabled.
- The installer is the only setup entry point: `/install`.
