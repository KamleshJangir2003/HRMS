<?php
/**
 * Test script to verify the welcome letter flow functionality
 * This script demonstrates the flow from selected interview to employee creation
 */

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Interview;
use App\Models\Employee;
use App\Models\Lead;

echo "=== Welcome Letter Flow Test ===\n\n";

// 1. Check for selected interviews that haven't received welcome letter
echo "1. Checking for selected interviews without welcome letter...\n";
$selectedInterviews = Interview::with('lead')
    ->where('result', 'Selected')
    ->where('welcome_letter_sent', false)
    ->get();

echo "Found " . $selectedInterviews->count() . " selected interviews pending welcome letter.\n\n";

// 2. Show what happens when welcome letter is sent
if ($selectedInterviews->count() > 0) {
    $interview = $selectedInterviews->first();
    echo "2. Sample interview data:\n";
    echo "   - Candidate: " . $interview->candidate_name . "\n";
    echo "   - Email: " . $interview->candidate_email . "\n";
    echo "   - Job Role: " . $interview->job_role . "\n";
    echo "   - Welcome Letter Sent: " . ($interview->welcome_letter_sent ? 'Yes' : 'No') . "\n\n";
    
    // Check if employee already exists
    $existingEmployee = Employee::where('email', $interview->candidate_email)->first();
    if ($existingEmployee) {
        echo "   - Employee record already exists: " . $existingEmployee->full_name . "\n";
    } else {
        echo "   - No employee record found - will be created when welcome letter is sent\n";
    }
}

// 3. Check employees in documents section
echo "\n3. Current employees in documents section:\n";
$employees = Employee::where('user_type', 'employee')->get();
echo "Found " . $employees->count() . " employees.\n";

foreach ($employees as $emp) {
    $isNew = $emp->created_at->diffInDays() < 7;
    echo "   - " . $emp->full_name . " (" . $emp->email . ")" . ($isNew ? " [NEW]" : "") . "\n";
}

// 4. Show recently added from interviews
echo "\n4. Recently added from interviews:\n";
$recentlyAdded = Interview::with('lead')
    ->where('welcome_letter_sent', true)
    ->where('result', 'Selected')
    ->orderBy('updated_at', 'desc')
    ->take(5)
    ->get();

echo "Found " . $recentlyAdded->count() . " recently processed interviews.\n";
foreach ($recentlyAdded as $interview) {
    echo "   - " . $interview->candidate_name . " (" . $interview->job_role . ") - Welcome letter sent\n";
}

echo "\n=== Test Complete ===\n";
echo "\nFlow Summary:\n";
echo "1. Selected employees appear on: /admin/interviews/selected\n";
echo "2. After welcome letter is sent:\n";
echo "   - Interview.welcome_letter_sent = true\n";
echo "   - Employee record is created\n";
echo "   - Employee appears on: /admin/employees/documents\n";
echo "   - Employee is removed from selected interviews page\n";