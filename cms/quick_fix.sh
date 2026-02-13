#!/bin/bash

echo "🚀 Quick Server Fix Script"
echo "=========================="

# 1. Set proper permissions
echo "1. Setting file permissions..."
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chmod -R 775 public/uploads

# Create directories if they don't exist
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
mkdir -p public/uploads

# 2. Set ownership (adjust www-data if needed)
echo "2. Setting ownership..."
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chown -R www-data:www-data public/uploads

# 3. Clear Laravel cache
echo "3. Clearing Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Regenerate cache
echo "4. Regenerating cache..."
php artisan config:cache
php artisan route:cache

echo "✅ Fix completed! Now test your application."