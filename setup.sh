#! /bin/bash

# Install php dependencies
composer install
composer dump-autoload

# Install javascript dependencies
npm install

# Run database migrations
php artisan migrate

# Create application key
php artisan key:generate