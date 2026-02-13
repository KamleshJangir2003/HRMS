<?php

echo "🔍 Excel Upload Functionality Check\n";
echo "===================================\n\n";

// Test 1: Check if sample CSV file exists
$sampleFile = __DIR__ . '/public/sample_leads.csv';
if (file_exists($sampleFile)) {
    echo "✅ Sample CSV file exists\n";
    echo "   Content preview:\n";
    $content = file_get_contents($sampleFile);
    echo "   " . substr($content, 0, 100) . "...\n\n";
} else {
    echo "❌ Sample CSV file not found at: " . $sampleFile . "\n\n";
}

// Test 2: Check JavaScript file
$jsFile = __DIR__ . '/public/js/excel-upload.js';
if (file_exists($jsFile)) {
    echo "✅ JavaScript file exists\n";
    $jsContent = file_get_contents($jsFile);
    if (strpos($jsContent, '/save-manual-lead') !== false) {
        echo "✅ Manual lead route is correct\n";
    } else {
        echo "❌ Manual lead route not found\n";
    }
    if (strpos($jsContent, '/admin/leads/upload') !== false) {
        echo "✅ Excel upload route is correct\n";
    } else {
        echo "❌ Excel upload route not found\n";
    }
} else {
    echo "❌ JavaScript file not found\n";
}

echo "\n📋 Routes to check in web.php:\n";
echo "- Route::post('/admin/leads/upload', [LeadController::class, 'uploadExcel'])\n";
echo "- Route::post('/save-manual-lead', [LeadController::class, 'saveManualLead'])\n";

echo "\n🔧 Troubleshooting Steps:\n";
echo "1. Open browser developer tools (F12)\n";
echo "2. Go to Console tab\n";
echo "3. Try uploading Excel file\n";
echo "4. Check for any JavaScript errors\n";
echo "5. Check Network tab for failed requests\n";
echo "6. Make sure CSRF token is present in page\n";

echo "\n✅ Files checked successfully!\n";