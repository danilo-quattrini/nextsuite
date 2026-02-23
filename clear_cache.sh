#!/bin/zsh

# Clear everything
php artisan optimize:clear

# Or clear individually
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "Cleaned the cache!"

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "Rebuild the caches!"

# Verify cache was rebuilt
ls -la bootstrap/cache/
echo "Check if the cash as been rebuilt!"
