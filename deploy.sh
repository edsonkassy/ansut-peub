#!/bin/bash
cd /var/www/ansut-peub-v0

# Set permissions
chown -R www-data:www-data .
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Install/update composer dependencies
# Using --no-dev is recommended for production environments
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Install/update npm dependencies and build assets for production
npm install
npm run build

# Run database migrations with fresh start and seeders
# The --force flag is to run migrations in production without prompt
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Restart services
systemctl restart nginx
systemctl restart php8.2-fpm

echo "Application deployed successfully!" 