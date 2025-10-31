# How to Run the Example Apps (macOS / zsh)

This document covers quick steps to run the `before-app` and `after-app` on your local machine. Both folders are minimal Laravel project snapshots and assume you have PHP, Composer, and a database available.

Prerequisites
- PHP 8.1+ (or compatible with your Laravel version)
- Composer
- A supported DB (MySQL, Postgres, or SQLite)
- Node/npm if you plan to run frontend assets (not required for the server examples)

General notes
- These example directories are not full scaffolding of a complete Laravel project (they are part of the case study). If you want a runnable project, you can create a fresh Laravel project and copy the `app/`, `routes/` and `database/` files into it.

Example quick start (recommended: create a fresh Laravel project and copy the case study files in):

1) Create a new Laravel project (if you don't already have one):

```zsh
composer create-project laravel/laravel my-ecommerce
cd my-ecommerce
```

2) Copy files from the `before-app` or `after-app` into this new project:
- Copy `app/Models`, `app/Http/Controllers/OrderController.php`, `app/Services` (after-app), `app/Repositories` (after-app), `app/Events` (after-app), `app/Listeners` (after-app), `app/Mail` (after-app), `database/migrations/*`, and `routes/web.php`.

3) Install any required packages (example):

```zsh
# If using Sanctum or other features referenced in the sample User model
composer require laravel/sanctum
```

4) Configure your `.env` file (DB connection, mail driver, queue driver):
- For quick local usage, use SQLite in-memory for tests or a local sqlite file for dev:

```zsh
DB_CONNECTION=sqlite
DB_DATABASE=${PWD}/database/database.sqlite

# Mail trap or log driver
MAIL_MAILER=log

# Queue driver (for queued listeners)
QUEUE_CONNECTION=database
```

5) Create SQLite file and run migrations:

```zsh
# from project root
touch database/database.sqlite
php artisan migrate
```

6) (Optional) Seed some products. You can create a quick tinker script or seeder to add sample products:

```zsh
php artisan tinker
>>> \App\Models\Product::create(['name' => 'Widget', 'price' => 19.99, 'stock' => 100]);
```

7) Run the application locally:

```zsh
php artisan serve
# Open http://127.0.0.1:8000 in your browser
```

8) If you are using queued listeners (recommended for the After app), run a queue worker in another terminal:

```zsh
php artisan queue:table
php artisan migrate
php artisan queue:work --tries=3
```

Mail debugging
- We set `MAIL_MAILER=log` for local dev. Check `storage/logs/laravel.log` to see the rendered email content.

Notes on running the case-study directories directly
- If you prefer to run `before-app` or `after-app` as-is, create a new Laravel project and copy files from those folders into the corresponding folders of the new project. The docs and migration summaries in this repo are meant to be instructional; they are not full, runnable composer project roots by themselves.

Troubleshooting
- If you get model/namespace errors after copying, ensure `namespace App\...` and autoloading are consistent. Run `composer dump-autoload`.
- If a listener is not running, ensure the `QUEUE_CONNECTION` is configured and a worker is running.

That’s it — if you want, I can prepare a small `seed` file and a `docker-compose` stack to make this fully reproducible in a containerized environment.
