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

## Setup

Copy `.env.example` to `.env` and set your database credentials.

```bash
composer install
php artisan key:generate
```

Import `installation/backup/database.sql` before running migrations. Do not migrate on an empty database.

```bash
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

Frontend (Builder storefront):

```bash
npm install
npm run build
```

Point the web server document root at `public/`.

## Useful commands

```bash
php artisan serve
npm run dev
php artisan optimize:clear
```

## Notes

- Configure payment, mail, SMS, and Firebase keys from the admin panel. Do not commit secrets.
- License phone-home, installer-only routing, ZIP self-update extraction, and demo database wipe are disabled.
