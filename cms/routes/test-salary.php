<?php

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\SalaryRecord;
use Illuminate\Support\Facades\Route;

Route::get('/test-salary-system', function() {
    $output = [];
    
    // Check employees
    $employeeCount = Employee::where('user_type', 'employee')->count();
    $employeesWithSalary = Employee::where('user_type', 'employee')->whereNotNull('basic_salary')->count();
    $output[] = "Total Employees: {$employeeCount}";
    $output[] = "Employees with Basic Salary: {$employeesWithSalary}";
    
    // Check attendance
    $attendanceCount = Attendance::count();
    $currentMonthAttendance = Attendance::whereYear('attendance_date', date('Y'))
        ->whereMonth('attendance_date', date('m'))
        ->count();
    $output[] = "Total Attendance Records: {$attendanceCount}";
    $output[] = "Current Month Attendance: {$currentMonthAttendance}";
    
    // Check salary records
    $salaryRecordCount = SalaryRecord::count();
    $output[] = "Total Salary Records: {$salaryRecordCount}";
    
    // Sample employees
    $employees = Employee::where('user_type', 'employee')
        ->whereNotNull('basic_salary')
        ->limit(3)
        ->get(['id', 'first_name', 'last_name', 'basic_salary', 'shift']);
    
    $output[] = "\nSample Employees:";
    foreach($employees as $emp) {
        $output[] = "- {$emp->first_name} {$emp->last_name} (ID: {$emp->id}) - Salary: ₹{$emp->basic_salary} - Shift: {$emp->shift}";
    }
    
    return '<pre>' . implode("\n", $output) . '</pre><br><a href="/admin/salary">Go to Salary Management</a>';
})->middleware(['auth', 'check.user.type:admin']);