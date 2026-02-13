<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\SalaryRecord;
use Illuminate\Support\Facades\DB;

echo "=== DEBUGGING SALARY ISSUE ===\n\n";

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

echo "Checking for Month: $currentMonth, Year: $currentYear\n\n";

// 1. Check total employees
$totalEmployees = Employee::where('user_type', 'employee')->count();
echo "1. Total Employees (user_type = 'employee'): $totalEmployees\n\n";

// 2. List all employees with their salary info
echo "2. All Employees with Salary Info:\n";
echo "ID | Name | Department | In-Hand Salary | Basic Salary | CTC\n";
echo "---|------|------------|----------------|--------------|----\n";

$employees = Employee::where('user_type', 'employee')
    ->select('id', 'first_name', 'last_name', 'department', 'in_hand_salary', 'basic_salary', 'current_ctc')
    ->orderBy('first_name')
    ->get();

foreach ($employees as $emp) {
    $inHand = $emp->in_hand_salary ?? 'NULL';
    $basic = $emp->basic_salary ?? 'NULL';
    $ctc = $emp->current_ctc ?? 'NULL';
    echo "{$emp->id} | {$emp->first_name} {$emp->last_name} | {$emp->department} | {$inHand} | {$basic} | {$ctc}\n";
}

echo "\n";

// 3. Check salary records for current month
$salaryRecords = SalaryRecord::where('month', $currentMonth)
    ->where('year', $currentYear)
    ->with('employee')
    ->get();

echo "3. Salary Records for Month $currentMonth, Year $currentYear: " . $salaryRecords->count() . "\n";
echo "Employee ID | Employee Name | Net Salary\n";
echo "------------|---------------|----------\n";

foreach ($salaryRecords as $record) {
    $empName = $record->employee ? $record->employee->first_name . ' ' . $record->employee->last_name : 'DELETED';
    echo "{$record->employee_id} | {$empName} | {$record->net_salary}\n";
}

echo "\n";

// 4. Find missing employees
echo "4. Employees Missing from Salary Records:\n";
$employeeIds = $employees->pluck('id')->toArray();
$salaryEmployeeIds = $salaryRecords->pluck('employee_id')->toArray();
$missingIds = array_diff($employeeIds, $salaryEmployeeIds);

if (empty($missingIds)) {
    echo "No employees missing from salary records.\n";
} else {
    echo "Missing Employee IDs: " . implode(', ', $missingIds) . "\n";
    
    foreach ($missingIds as $missingId) {
        $emp = $employees->where('id', $missingId)->first();
        if ($emp) {
            $inHand = $emp->in_hand_salary ?? 'NULL';
            $basic = $emp->basic_salary ?? 'NULL';
            echo "- ID {$missingId}: {$emp->first_name} {$emp->last_name} (In-Hand: {$inHand}, Basic: {$basic})\n";
        }
    }
}

echo "\n";

// 5. Check attendance records for missing employees
if (!empty($missingIds)) {
    echo "5. Attendance Records for Missing Employees:\n";
    
    foreach ($missingIds as $missingId) {
        $emp = $employees->where('id', $missingId)->first();
        if ($emp) {
            $attendanceCount = DB::table('attendance')
                ->where('employee_id', $missingId)
                ->whereMonth('attendance_date', $currentMonth)
                ->whereYear('attendance_date', $currentYear)
                ->count();
            
            echo "- {$emp->first_name} {$emp->last_name} (ID: {$missingId}): {$attendanceCount} attendance records\n";
        }
    }
}

echo "\n=== DEBUG COMPLETE ===\n";