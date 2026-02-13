# Salary/Payroll Module for Laravel CMS

## Overview
This is a complete Salary/Payroll management system for your Laravel CMS that calculates employee salaries based on attendance records.

## Features
- ✅ Monthly salary calculation based on attendance
- ✅ Support for multiple attendance statuses (Present, Absent, Half Day, Paid Leave, etc.)
- ✅ Day/Night shift support
- ✅ Automatic deduction calculation
- ✅ Salary slip generation
- ✅ Duplicate prevention (one salary per employee per month)
- ✅ Bootstrap 5 responsive UI
- ✅ Clean, production-ready code

## Files Created/Modified

### 1. Database Migrations
- `2026_02_12_000000_create_salary_records_table.php` - Creates salary_records table
- `2026_02_12_000001_add_salary_fields_to_employees_table.php` - Adds basic_salary, job_title, shift to employees
- `2026_02_12_000002_update_attendance_status_enum.php` - Updates attendance status options

### 2. Models
- `app/Models/SalaryRecord.php` - Salary record model
- `app/Models/Attendance.php` - Attendance model
- Updated `app/Models/Employee.php` - Added relationships and salary fields

### 3. Controller
- `app/Http/Controllers/Admin/SalaryController.php` - Complete salary management logic

### 4. Views
- `resources/views/admin/salary/index.blade.php` - Main salary management page
- `resources/views/admin/salary/view.blade.php` - Detailed salary view
- `resources/views/admin/salary/slip.blade.php` - Printable salary slip

### 5. Routes
- Added salary management routes to `routes/web.php`

### 6. Sidebar
- Updated `resources/views/auth/layouts/sidebar.blade.php` - Added salary management link

### 7. Test Data
- `database/seeders/SalaryTestDataSeeder.php` - Sample employees and attendance data

## Database Schema

### salary_records table
```sql
- id (primary key)
- employee_id (foreign key to employees)
- month (1-12)
- year (e.g., 2024)
- basic_salary (decimal)
- working_days (decimal, supports 0.5 for half days)
- deduction (decimal)
- net_salary (decimal)
- shift (Day/Night)
- created_at, updated_at
- Unique constraint: employee_id + month + year
```

### employees table (added fields)
```sql
- basic_salary (decimal) - Employee's monthly CTC
- job_title (string) - Employee designation
- shift (enum: Day/Night) - Employee shift
```

### attendance table (updated)
```sql
- status enum now includes: Present, Absent, Half Day, Unauthorized Leave, Paid Leave, Holiday, Week Off, Comp Off
- shift (Day/Night)
```

## Salary Calculation Logic

### Working Days Calculation
```
Working Days = Present + Paid Leave + Comp Off + (Half Day × 0.5)
```

### Deduction Calculation
```
Per Day Salary = basic_salary ÷ total_days_in_month
Deduction = (Absent × Per Day Salary) + (Unauthorized Leave × Per Day Salary) + (Half Day × Per Day Salary ÷ 2)
```

### Net Salary
```
Net Salary = Basic Salary - Deduction
```

## Installation & Setup

### 1. Run Migrations
```bash
cd cms
php artisan migrate
```

### 2. Create Sample Data (Optional)
```bash
php artisan db:seed --class=SalaryTestDataSeeder
```

### 3. Test the System
Visit: `/test-salary-system` (admin login required)

## Usage

### 1. Access Salary Management
- Login as admin
- Go to Sidebar → Payroll → Salary Management
- Or visit: `/admin/salary`

### 2. Generate Salary
1. Select Month and Year
2. Click "Generate Salary" button
3. System will calculate salary for all employees with basic_salary
4. Prevents duplicate generation for same month/year

### 3. View Salary Details
- Click "View" button next to any employee
- See detailed attendance breakdown
- View salary calculation steps

### 4. Generate Salary Slip
- Click "Slip" button to generate printable salary slip
- Opens in new tab with print dialog

## Key Features

### 1. Attendance Status Support
- **Present**: Full day attendance
- **Absent**: No attendance (full deduction)
- **Half Day**: 50% attendance (50% deduction)
- **Paid Leave**: Counted as working day (no deduction)
- **Comp Off**: Compensatory off (no deduction)
- **Unauthorized Leave**: Full deduction
- **Holiday**: No deduction
- **Week Off**: No deduction

### 2. Shift Support
- **Day Shift**: Regular IST timing
- **Night Shift**: US timezone support

### 3. Duplicate Prevention
- Unique constraint prevents duplicate salary generation
- Shows appropriate error messages

### 4. Responsive UI
- Bootstrap 5 responsive design
- Mobile-friendly interface
- Clean, professional look

## API Endpoints

### GET `/admin/salary`
- Main salary management page
- Supports month/year filtering

### POST `/admin/salary/generate`
- Generates salary for selected month/year
- Parameters: month, year

### GET `/admin/salary/{id}/view`
- Detailed salary view for specific record

### GET `/admin/salary/{id}/slip`
- Printable salary slip

## Security Features
- Admin authentication required
- CSRF protection on all forms
- Input validation
- SQL injection prevention via Eloquent ORM

## Performance Optimizations
- Efficient database queries with relationships
- Batch processing for salary generation
- Indexed database columns
- Minimal memory usage

## Customization

### 1. Salary Calculation Logic
Edit `SalaryController::generate()` method to modify calculation rules.

### 2. Salary Slip Design
Modify `resources/views/admin/salary/slip.blade.php` for custom slip design.

### 3. Additional Fields
Add more fields to `salary_records` table and update the model/controller accordingly.

## Troubleshooting

### 1. Migration Issues
```bash
php artisan migrate:status
php artisan migrate --path=database/migrations/2026_02_12_000000_create_salary_records_table.php
```

### 2. No Employees Found
- Ensure employees have `basic_salary` field filled
- Check `user_type = 'employee'`
- Run the seeder for test data

### 3. Attendance Data Missing
- Ensure attendance records exist for the selected month
- Check attendance table has proper status values

### 4. UI Issues
- Clear browser cache
- Check Bootstrap 5 CDN is loading
- Verify FontAwesome icons are loading

## Sample Data Structure

### Employee Record
```php
[
    'first_name' => 'John',
    'last_name' => 'Doe',
    'basic_salary' => 50000.00,
    'job_title' => 'Software Developer',
    'shift' => 'Day',
    'department' => 'IT'
]
```

### Attendance Record
```php
[
    'employee_id' => 1,
    'attendance_date' => '2024-02-15',
    'status' => 'Present',
    'shift' => 'Day',
    'in_time' => '09:00:00',
    'out_time' => '18:00:00'
]
```

### Salary Record
```php
[
    'employee_id' => 1,
    'month' => 2,
    'year' => 2024,
    'basic_salary' => 50000.00,
    'working_days' => 22.5,
    'deduction' => 1724.14,
    'net_salary' => 48275.86,
    'shift' => 'Day'
]
```

## Support
For any issues or customizations, refer to the Laravel documentation or contact the development team.

---

**Note**: This module is production-ready and follows Laravel best practices. All code is optimized for performance and security.