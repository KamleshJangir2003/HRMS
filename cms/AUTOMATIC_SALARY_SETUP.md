# Automatic Monthly Salary Generation Setup

## Overview
यह system automatically महीना complete होने पर attendance data को salary section में transfer कर देता है।

## Features
1. **Automatic Generation**: महीना complete होने पर automatic salary generate होती है
2. **Manual Trigger**: Admin manually भी salary generate कर सकता है
3. **Notification System**: नई salary generate होने पर notification दिखता है
4. **Scheduled Task**: Windows Task Scheduler के साथ setup कर सकते हैं

## Setup Instructions

### 1. Laravel Scheduler Setup (Recommended)
```bash
# Laravel scheduler को enable करने के लिए Windows Task Scheduler में यह command add करें:
# Run every minute:
php "c:\Users\Admin\Downloads\kwikster-web\cms\artisan" schedule:run
```

### 2. Manual Windows Task Scheduler Setup
1. Windows Task Scheduler खोलें
2. "Create Basic Task" पर click करें
3. Name: "Monthly Salary Generation"
4. Trigger: Monthly (1st day of month at 9:00 AM)
5. Action: Start a program
6. Program: `c:\Users\Admin\Downloads\kwikster-web\cms\generate_monthly_salary.bat`

### 3. Manual Command
```bash
# Manually run करने के लिए:
php artisan salary:generate-monthly
```

## How It Works

### Automatic Trigger
- जब attendance save होती है, system check करता है कि month complete हुआ या नहीं
- अगर month complete है और salary generate नहीं हुई, तो automatically generate हो जाती है

### Manual Trigger
- Admin panel में "Auto Generate" button से manually trigger कर सकते हैं
- यह last month की salary generate करता है

### Notification
- नई salary generate होने पर admin को notification दिखता है
- Salary index page पर blue alert box में information दिखती है

## Files Modified/Created

### New Files:
1. `app/Console/Commands/GenerateMonthlySalary.php` - Command for salary generation
2. `app/Console/Kernel.php` - Scheduler configuration
3. `generate_monthly_salary.bat` - Windows batch script

### Modified Files:
1. `app/Http/Controllers/Admin/AttendanceController.php` - Added auto-trigger logic
2. `app/Http/Controllers/Admin/SalaryController.php` - Added notification system
3. `resources/views/admin/salary/index.blade.php` - Added notification and manual trigger
4. `routes/web.php` - Added manual trigger route

## Testing
1. Attendance save करें month के last day पर
2. Check करें कि salary automatically generate हुई या नहीं
3. Manual trigger button test करें
4. Notification check करें salary page पर

## Troubleshooting
- अगर automatic generation काम नहीं कर रहा, manual trigger use करें
- Log file check करें: `salary_generation.log`
- Database में `salary_records` table check करें