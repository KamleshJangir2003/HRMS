# Server Deployment Checklist for Lead Upload Fix

## 1. Database Configuration
- [ ] Verify database credentials in .env file
- [ ] Test database connection using test_db_connection.php
- [ ] Ensure leads table exists and has correct structure
- [ ] Check database user permissions (INSERT, SELECT, UPDATE, DELETE)

## 2. File Permissions
- [ ] Set proper permissions for storage directory: `chmod -R 775 storage/`
- [ ] Set proper permissions for bootstrap/cache: `chmod -R 775 bootstrap/cache/`
- [ ] Set proper permissions for public/uploads: `chmod -R 775 public/uploads/`
- [ ] Ensure web server can write to these directories

## 3. PHP Configuration
- [ ] Verify PHP version (minimum 8.1)
- [ ] Check required PHP extensions:
  - [ ] PDO MySQL
  - [ ] Mbstring
  - [ ] OpenSSL
  - [ ] Tokenizer
  - [ ] XML
  - [ ] Ctype
  - [ ] JSON
  - [ ] BCMath
  - [ ] Fileinfo
  - [ ] ZIP (for Excel processing)

## 4. Laravel Configuration
- [ ] Run: `php artisan config:cache`
- [ ] Run: `php artisan route:cache`
- [ ] Run: `php artisan view:cache`
- [ ] Ensure APP_KEY is set in .env
- [ ] Set APP_DEBUG=false for production

## 5. Database Migration
- [ ] Run: `php artisan migrate --force`
- [ ] Verify all tables are created properly
- [ ] Check if leads table has all required columns

## 6. Testing
- [ ] Run debug_leads.php to test functionality
- [ ] Test lead upload through web interface
- [ ] Check Laravel logs for any errors
- [ ] Verify leads are being saved to database

## 7. Web Server Configuration
- [ ] Ensure document root points to public/ directory
- [ ] Configure URL rewriting for Laravel routes
- [ ] Set appropriate memory limits for file uploads
- [ ] Configure max file upload size

## Commands to Run on Server:

```bash
# Navigate to project directory
cd /path/to/kwikster-web/cms

# Install/update dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chmod -R 775 storage bootstrap/cache public/uploads
chown -R www-data:www-data storage bootstrap/cache public/uploads

# Clear and cache configuration
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Test database connection
php test_db_connection.php

# Test lead functionality
php debug_leads.php
```

## Troubleshooting Common Issues:

1. **Permission Denied Errors**
   - Check file/directory permissions
   - Ensure web server user owns the files

2. **Database Connection Failed**
   - Verify database credentials
   - Check if database server is running
   - Ensure database user has proper permissions

3. **File Upload Issues**
   - Check PHP upload_max_filesize setting
   - Verify post_max_size setting
   - Ensure uploads directory exists and is writable

4. **Class Not Found Errors**
   - Run `composer dump-autoload`
   - Clear application cache

5. **Migration Errors**
   - Check if tables already exist
   - Verify database user has CREATE/ALTER permissions