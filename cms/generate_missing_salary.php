<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;
use App\Models\SalaryRecord;
use App\Models\Attendance;
use Carbon\Carbon;

echo "=== GENERATING SALARY FOR MISSING EMPLOYEE ===\n\n";

$employeeId = 16; // Missing employee ID
$month = 2; // February
$year = 2026;

// Get the employee
$employee = Employee::find($employeeId);

if (!$employee) {
    echo "Employee with ID $employeeId not found!\n";
    exit;
}

echo "Employee: {$employee->first_name} {$employee->last_name}\n";
echo "In-Hand Salary: {$employee->in_hand_salary}\n";
echo "Basic Salary: " . ($employee->basic_salary ?? 'NULL') . "\n\n";

// Check if salary already exists
$existingRecord = SalaryRecord::where('employee_id', $employeeId)
    ->where('month', $month)
    ->where('year', $year)
    ->first();

if ($existingRecord) {
    echo "Salary record already exists for this employee!\n";
    exit;
}

// Calculate salary components
$inHandSalary = $employee->in_hand_salary;

if (!$inHandSalary || $inHandSalary <= 0) {
    echo "Employee has no valid in-hand salary!\n";
    exit;
}

// Calculate gross from in-hand (simplified version)
function calculateGrossFromInHand($inHand) {
    $gross = $inHand;
    
    for ($i = 0; $i < 10; $i++) {
        $basic = $gross * 0.60;
        $pfBasic = ($basic >= 15000) ? 15000 : $basic;
        $employeePf = $pfBasic * 0.12;
        
        $employeeEsic = ($gross <= 21000) ? $gross * 0.0075 : 0;
        
        $calculatedInHand = $gross - $employeePf - $employeeEsic;
        
        if (abs($calculatedInHand - $inHand) < 0.01) {
            break;
        }
        
        $gross = $gross + ($inHand - $calculatedInHand);
    }
    
    return round($gross, 2);
}

$gross = calculateGrossFromInHand($inHandSalary);
$basic = $gross * 0.60;
$hra = $gross * 0.40;

// PF calculations with cap rule
$pfBasic = ($basic >= 15000) ? 15000 : $basic;
$employeePf = $pfBasic * 0.12;
$employerPf = $pfBasic * 0.13;

// ESIC calculations (only if Gross <= 21000)
if ($gross <= 21000) {
    $employeeEsic = $gross * 0.0075;
    $employerEsic = $gross * 0.0325;
} else {
    $employeeEsic = 0;
    $employerEsic = 0;
}

echo "Calculated Components:\n";
echo "Gross: $gross\n";
echo "Basic: $basic\n";
echo "Employee PF: $employeePf\n";
echo "Employee ESIC: $employeeEsic\n\n";

// Get attendance records for the month
$attendanceRecords = Attendance::where('employee_id', $employeeId)
    ->whereYear('attendance_date', $year)
    ->whereMonth('attendance_date', $month)
    ->get();

echo "Attendance Records: " . $attendanceRecords->count() . "\n";

// Count different attendance statuses
$present = $attendanceRecords->where('status', 'Present')->count();
$absent = $attendanceRecords->where('status', 'Absent')->count();
$halfDay = $attendanceRecords->where('status', 'Half Day')->count();
$unauthorizedLeave = $attendanceRecords->where('status', 'Unauthorized Leave')->count();
$paidLeave = $attendanceRecords->where('status', 'Paid Leave')->count();
$holiday = $attendanceRecords->where('status', 'Holiday')->count();
$weekOff = $attendanceRecords->where('status', 'Week Off')->count();
$compOff = $attendanceRecords->where('status', 'Comp Off')->count();

echo "Present: $present\n";
echo "Absent: $absent\n";
echo "Half Day: $halfDay\n";
echo "Paid Leave: $paidLeave\n";
echo "Holiday: $holiday\n";
echo "Week Off: $weekOff\n";
echo "Comp Off: $compOff\n";
echo "Unauthorized Leave: $unauthorizedLeave\n\n";

// Calculate working days
$workingDays = $present + $paidLeave + $compOff + ($halfDay * 0.5);

// Calculate total days in month
$totalDaysInMonth = Carbon::create($year, $month)->daysInMonth;

// Calculate per day salary
$perDaySalary = $inHandSalary / $totalDaysInMonth;

// Calculate salary based on working days only (proportional)
$earnedSalary = $workingDays * $perDaySalary;

// No deductions - just pay for actual working days
$deduction = $inHandSalary - $earnedSalary;
$netSalary = $earnedSalary;

echo "Salary Calculation:\n";
echo "Total Days in Month: $totalDaysInMonth\n";
echo "Working Days: $workingDays\n";
echo "Per Day Salary: $perDaySalary\n";
echo "Earned Salary: $earnedSalary\n";
echo "Deduction: $deduction\n";
echo "Net Salary: $netSalary\n\n";

// Create salary record
try {
    $salaryRecord = SalaryRecord::create([
        'employee_id' => $employeeId,
        'month' => $month,
        'year' => $year,
        'basic_salary' => $inHandSalary, // Store in_hand_salary amount
        'working_days' => $workingDays,
        'deduction' => $deduction,
        'advance' => 0, // Default values
        'incentive' => 0,
        'employee_pf' => $employeePf,
        'employee_esi' => $employeeEsic,
        'employer_pf' => $employerPf,
        'employer_esi' => $employerEsic,
        'net_salary' => $netSalary,
        'shift' => $employee->shift ?? 'Day'
    ]);
    
    echo "SUCCESS: Salary record created with ID: {$salaryRecord->id}\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== COMPLETE ===\n";