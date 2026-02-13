<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Employee;

echo "=== Employee Password Status ===\n";

$employees = Employee::where('user_type', 'employee')
    ->select('id', 'first_name', 'last_name', 'email', 'temp_password', 'password')
    ->get();

foreach($employees as $emp) {
    echo "ID: {$emp->id} | Name: {$emp->first_name} {$emp->last_name}\n";
    echo "Email: {$emp->email}\n";
    echo "Temp Password: " . ($emp->temp_password ?: 'NULL') . "\n";
    echo "Password Set: " . ($emp->password ? 'YES' : 'NO') . "\n";
    echo "---\n";
}

echo "\nTotal Employees: " . $employees->count() . "\n";
echo "With Temp Password: " . $employees->whereNotNull('temp_password')->count() . "\n";