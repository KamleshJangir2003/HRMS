@echo off
echo ========================================
echo    Monthly Salary Generation Tool
echo ========================================
echo.

cd /d "c:\Users\Admin\Downloads\kwikster-web\cms"

echo Generating salary for last month...
php artisan salary:generate-monthly

echo.
echo ========================================
echo    Salary Generation Complete!
echo ========================================
echo.
echo Press any key to exit...
pause > nul