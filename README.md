# Link-Hub

## Requirements

  * PHP 8.2 or higher
  * NPM
  * Keycloak (user management)
  * [Flux](https://fluxui.dev/) Pro License

## Installation

```
git clone https://github.com/schlagma/link-hub
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan flux:activate
```
