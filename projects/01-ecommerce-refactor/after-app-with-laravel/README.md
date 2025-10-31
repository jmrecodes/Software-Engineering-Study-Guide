## Laravel After App

This directory hosts the "After" version of the e-commerce shopping cart case study. It is a fully bootstrapped Laravel application with the refactored architecture wired in (services, repositories, events, listeners, policies, tests, and seeders).

### Requirements
- PHP 8.2+
- Composer
- SQLite (default) or MySQL (via the repo-level Docker Compose stack)

### Setup
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

The `.env.example` defaults to SQLite. To switch to MySQL, set the `DB_*` variables accordingly (for Docker use `DB_HOST=db`, `DB_DATABASE=laravel`, `DB_USERNAME=laravel`, `DB_PASSWORD=secret`).

### Running the Queue Worker
Side effects (email + inventory updates) are handled via queued listeners. In development you can run them synchronously (`QUEUE_CONNECTION=sync`). For async processing start the worker:
```bash
php artisan queue:work --tries=3
```

### Tests
```bash
php artisan test --testsuite=Feature
```

### Docker
From the repository root you can spin everything up with php-fpm, nginx, MySQL, Redis, and the queue worker:
```bash
docker-compose up --build
```

The application will be available on http://localhost:8080 (nginx) or http://localhost:8000 when using `php artisan serve` locally.
