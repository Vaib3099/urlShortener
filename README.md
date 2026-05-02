# URL Shortener

A lightweight URL shortener built with Laravel. Create short links, manage users/clients, and track redirects.

**Repository structure (high level)**
- app/ — application code (models, controllers, mail, providers)
- database/ — migrations, factories, seeders
- resources/ — views, frontend assets
- routes/ — application routes (web.php, api.php)

## Features
- Generate short codes for long URLs
- Redirect short codes to original URLs with tracking
- Admin interface to manage URLs, clients, and users
- Role-based access control
- Seeders for initial roles and admin user

## Requirements
- PHP 8.1+
- Composer
- MySQL or PostgreSQL
- Node.js & npm (for frontend tooling)

## Quick Start
1. Clone the repository

   git clone <repo-url>
   cd urlShortner

2. Install PHP dependencies

   composer install

3. Install JS dependencies and build assets

   npm install
   npm run build

4. Copy environment file and generate app key

   cp .env.example .env
   php artisan key:generate

5. Configure the .env database values (DB_CONNECTION, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD) and other settings like APP_URL.

6. Run migrations and seeders

   php artisan migrate --seed

7. Start the local server

   php artisan serve

Open http://127.0.0.1:8000 (or your APP_URL) to use the app.

## Development
- Create a feature branch for changes
- Run the local dev server and Vite during development:

   php artisan serve
   npm run dev

## Testing
Run the test suite:

   php artisan test

## Deployment Notes
- Ensure the .env is set for production with correct DB and APP_URL.
- Configure a web server (Nginx/Apache) to point to public/.
- Run composer install --no-dev, php artisan migrate --force, and build frontend assets.

## Contributing
Contributions and issues are welcome. Please open a PR with a clear description and tests when applicable.

## License
This project is licensed under the MIT License.

