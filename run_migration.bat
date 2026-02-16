@echo off
echo Running Activity Logs Migration...
cd /d "c:\Users\Admin\Downloads\kwikster-web\cms"
php artisan migrate --path=database/migrations/2024_01_01_000000_create_activity_logs_table.php
echo Migration completed!
pause