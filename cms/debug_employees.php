<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$employees = App\Models\Employee::where('user_type', 'employee')->take(5)->get();

foreach($employees as $emp) {
    echo "ID: {$emp->id}\n";
    echo "First Name: '{$emp->first_name}'\n";
    echo "Last Name: '{$emp->last_name}'\n";
    echo "Full Name: '{$emp->full_name}'\n";
    echo "Email: {$emp->email}\n";
    echo "---\n";
}