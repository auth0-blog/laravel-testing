This repo demonstrates how to test APIs in Laravel using PHPUnit. To see an in depth explanation and how to set it up on your own, [check out the full tutorial here](2020-12-18-testing-laravel-apis-with-phpunit).

## Setting Up

Clone the repo and install dependencies:

```bash
git clone git@github.com:auth0-blog/laravel-testing.git
cd laravel-testing
composer install
```

Set up `.env` file:

```bash
cp .env.example .env
```

Create the database for your application and swap `YOUR_DATABASE_NAME`, `YOUR_DATABASE_USERNAME`, and `YOUR_DATABASE_PASSWORD` with the appropriate values for your database:

```
DB_CONNECTION=YOUR_DB_CONNECTOR
DB_PORT=3306 # 3306 for MySQL
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USERNAME
DB_PASSWORD=YOUR_DATABASE_PASSWORD
```

Seed your database:

```bash
php artisan migrate --seed
```

Start your application"

```bash
php artisan serve
```

For troubleshooting, please [see the full tutorial](https://auth0.com/blog/testing-laravel-apis-with-phpunit/#Set-Up-the-Demo-Project).
