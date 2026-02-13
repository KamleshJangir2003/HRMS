<?php

require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;

echo "=== EMPLOYEE ID 16 DATA ===\n\n";

$employee = Employee::find(16);

if (!$employee) {
    echo "Employee with ID 16 not found!\n";
    exit;
}

echo "Employee Details:\n";
echo "ID: {$employee->id}\n";
echo "Name: {$employee->first_name} {$employee->last_name}\n";
echo "Email: {$employee->email}\n";
echo "Phone: " . ($employee->phone ?? 'NULL') . "\n";
echo "Contact Number: " . ($employee->contact_number ?? 'NULL') . "\n";
echo "Department: " . ($employee->department ?? 'NULL') . "\n";
echo "DOB: " . ($employee->dob ?? 'NULL') . "\n";
echo "Gender: " . ($employee->gender ?? 'NULL') . "\n";
echo "Address: " . ($employee->address ?? 'NULL') . "\n";
echo "City: " . ($employee->city ?? 'NULL') . "\n";
echo "State: " . ($employee->state ?? 'NULL') . "\n";
echo "Pincode: " . ($employee->pincode ?? 'NULL') . "\n";
echo "Father Name: " . ($employee->father_name ?? 'NULL') . "\n";
echo "Mother Name: " . ($employee->mother_name ?? 'NULL') . "\n";
echo "Guardian Number: " . ($employee->guardian_number ?? 'NULL') . "\n";
echo "Bank Name: " . ($employee->bank_name ?? 'NULL') . "\n";
echo "Bank Account: " . ($employee->bank_account_number ?? 'NULL') . "\n";
echo "IFSC Code: " . ($employee->ifsc_code ?? 'NULL') . "\n";
echo "Joining Date: " . ($employee->joining_date ?? 'NULL') . "\n";
echo "Current CTC: " . ($employee->current_ctc ?? 'NULL') . "\n";
echo "In Hand Salary: " . ($employee->in_hand_salary ?? 'NULL') . "\n";
echo "Basic Salary: " . ($employee->basic_salary ?? 'NULL') . "\n";
echo "Job Title: " . ($employee->job_title ?? 'NULL') . "\n";
echo "Shift: " . ($employee->shift ?? 'NULL') . "\n";
echo "User Type: " . ($employee->user_type ?? 'NULL') . "\n";
echo "Is Approved: " . ($employee->is_approved ? 'Yes' : 'No') . "\n";
echo "Created At: {$employee->created_at}\n";
echo "Updated At: {$employee->updated_at}\n";

echo "\n=== COMPLETE ===\n";