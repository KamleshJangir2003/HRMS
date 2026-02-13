@echo off
cd /d "c:\Users\Admin\Downloads\kwikster-web\cms"
php artisan salary:generate-monthly
echo Monthly salary generation completed at %date% %time% >> salary_generation.log