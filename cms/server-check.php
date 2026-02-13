<?php
// Server Configuration Check for Excel Upload

echo "<h2>Server Configuration Check</h2>";

// Check PHP Extensions
echo "<h3>Required PHP Extensions:</h3>";
$required_extensions = ['zip', 'xml', 'gd', 'mbstring', 'fileinfo'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '✅ Loaded' : '❌ Missing';
    echo "- {$ext}: {$status}<br>";
}

// Check File Upload Settings
echo "<h3>File Upload Settings:</h3>";
echo "- upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "- post_max_size: " . ini_get('post_max_size') . "<br>";
echo "- max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "- memory_limit: " . ini_get('memory_limit') . "<br>";
echo "- max_execution_time: " . ini_get('max_execution_time') . " seconds<br>";

// Check Storage Permissions
echo "<h3>Storage Permissions:</h3>";
$storage_path = __DIR__ . '/storage';
if (is_dir($storage_path)) {
    $writable = is_writable($storage_path) ? '✅ Writable' : '❌ Not Writable';
    echo "- Storage folder: {$writable}<br>";
} else {
    echo "- Storage folder: ❌ Not Found<br>";
}

// Check Composer Dependencies
echo "<h3>Composer Dependencies:</h3>";
$composer_lock = __DIR__ . '/composer.lock';
if (file_exists($composer_lock)) {
    echo "- composer.lock: ✅ Found<br>";
    $lock_content = file_get_contents($composer_lock);
    if (strpos($lock_content, 'phpoffice/phpspreadsheet') !== false) {
        echo "- PhpSpreadsheet: ✅ Installed<br>";
    } else {
        echo "- PhpSpreadsheet: ❌ Not Found<br>";
    }
} else {
    echo "- composer.lock: ❌ Not Found<br>";
}

echo "<hr>";
echo "<p><strong>Upload this file to your server root and run it to check configuration.</strong></p>";
?>