# Laravel LowPricePhones - Setup

This project has been converted from Node.js/React to Laravel with Blade templates.

## Requirements

- PHP 8.2+
- Composer
- MySQL
- Apache/XAMPP (or `php artisan serve`)

## Installation

1. **Install PHP dependencies**

   ```bash
   composer install
   ```

2. **Environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure `.env`** – set your database credentials:

   ```
   DB_DATABASE=lowpricephones
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Create database**

   ```sql
   CREATE DATABASE lowpricephones;
   ```

5. **Run migrations**

   ```bash
   php artisan migrate
   ```

6. **Seed database (optional)**

   ```bash
   php artisan db:seed
   ```

7. **Start the server**

   ```bash
   php artisan serve
   ```

   Open: http://localhost:8000

## XAMPP

- Put the project in `htdocs/mobielshop`
- Configure Apache to point to `public/` (DocumentRoot)
- Or use: `php artisan serve` from the project root

## Project Structure

- `app/Http/Controllers/` – Controllers
- `app/Models/` – Eloquent models
- `resources/views/` – Blade templates
- `routes/web.php` – Web routes
- `database/migrations/` – Database migrations
