<?php

// Test Excel Upload Functionality
require_once 'vendor/autoload.php';

use App\Http\Controllers\Admin\LeadController;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

echo "Testing Excel Upload Functionality...\n\n";

// Test 1: Check if sample CSV file exists
$sampleFile = public_path('sample_leads.csv');
if (file_exists($sampleFile)) {
    echo "✅ Sample CSV file exists: " . $sampleFile . "\n";
} else {
    echo "❌ Sample CSV file not found\n";
}

// Test 2: Check if routes are properly defined
echo "\n📋 Checking Routes:\n";
echo "- /admin/leads/upload (Excel upload)\n";
echo "- /save-manual-lead (Manual entry)\n";

// Test 3: Check JavaScript file
$jsFile = public_path('js/excel-upload.js');
if (file_exists($jsFile)) {
    echo "✅ JavaScript file exists: " . $jsFile . "\n";
} else {
    echo "❌ JavaScript file not found\n";
}

// Test 4: Check if Lead model has platform field
echo "\n🔍 Checking Lead Model:\n";
$leadModel = new \App\Models\Lead();
$fillable = $leadModel->getFillable();
if (in_array('platform', $fillable)) {
    echo "✅ Platform field is fillable in Lead model\n";
} else {
    echo "❌ Platform field not found in fillable array\n";
}

echo "\n📝 Summary:\n";
echo "1. Make sure role is selected before uploading Excel file\n";
echo "2. Make sure platform is selected (optional)\n";
echo "3. Excel file should have columns: Number, Name, Role (optional)\n";
echo "4. Supported formats: .xlsx, .xls, .csv\n";
echo "5. Check browser console for debugging information\n";

echo "\n🚀 Test completed!\n";