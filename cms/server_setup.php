<?php
/**
 * Complete Server Setup & Health Check
 * Run this after uploading files to server
 */

echo "<h1>🚀 Kwikster CMS - Server Setup & Health Check</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";

$errors = [];
$warnings = [];
$success = [];

// 1. Create required directories
echo "<h2>1. Creating Required Directories</h2>";
$dirs = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions', 
    'storage/framework/views',
    'bootstrap/cache',
    'public/uploads',
    'public/uploads/resumes'
];

foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0775, true)) {
            echo "<span class='success'>✅ Created: $dir</span><br>";
        } else {
            echo "<span class='error'>❌ Failed to create: $dir</span><br>";
            $errors[] = "Cannot create directory: $dir";
        }
    } else {
        echo "<span class='success'>✅ Exists: $dir</span><br>";
    }
}

// 2. Set file permissions
echo "<h2>2. Setting File Permissions</h2>";
$permission_dirs = ['storage', 'bootstrap/cache', 'public/uploads'];
foreach ($permission_dirs as $dir) {
    if (is_writable($dir)) {
        echo "<span class='success'>✅ Writable: $dir</span><br>";
    } else {
        echo "<span class='error'>❌ Not writable: $dir</span><br>";
        $errors[] = "Directory not writable: $dir";
    }
}

// 3. Check PHP requirements
echo "<h2>3. PHP Requirements Check</h2>";
$php_version = phpversion();
echo "PHP Version: $php_version ";
if (version_compare($php_version, '8.1.0', '>=')) {
    echo "<span class='success'>✅</span><br>";
} else {
    echo "<span class='error'>❌ Requires PHP 8.1+</span><br>";
    $errors[] = "PHP version too old";
}

$required_extensions = ['pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'zip', 'fileinfo'];
foreach ($required_extensions as $ext) {
    echo "$ext: ";
    if (extension_loaded($ext)) {
        echo "<span class='success'>✅</span><br>";
    } else {
        echo "<span class='error'>❌</span><br>";
        $errors[] = "Missing PHP extension: $ext";
    }
}

// 4. Database connection test
echo "<h2>4. Database Connection Test</h2>";
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=u259894078_Kwikster232;charset=utf8mb4", "u259894078_Kwikster231", "Copy@75970");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<span class='success'>✅ Database connection successful</span><br>";
    
    // Check tables
    $tables = ['leads', 'callbacks', 'interested_candidates', 'users'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<span class='success'>✅ Table exists: $table</span><br>";
        } else {
            echo "<span class='warning'>⚠️ Table missing: $table</span><br>";
            $warnings[] = "Table missing: $table";
        }
    }
    
} catch (Exception $e) {
    echo "<span class='error'>❌ Database connection failed: " . $e->getMessage() . "</span><br>";
    $errors[] = "Database connection failed";
}

// 5. Test lead operations
echo "<h2>5. Lead Operations Test</h2>";
try {
    // Test insert
    $stmt = $pdo->prepare("INSERT INTO leads (name, number, role, condition_status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute(['Server Test Lead', '1234567890', 'Test Role', '']);
    
    if ($result) {
        echo "<span class='success'>✅ Lead insert test passed</span><br>";
        
        // Test update
        $updateStmt = $pdo->prepare("UPDATE leads SET condition_status = ? WHERE name = ? AND number = ?");
        $updateResult = $updateStmt->execute(['Interested', 'Server Test Lead', '1234567890']);
        
        if ($updateResult) {
            echo "<span class='success'>✅ Lead update test passed</span><br>";
        }
        
        // Cleanup
        $pdo->exec("DELETE FROM leads WHERE name = 'Server Test Lead' AND number = '1234567890'");
        echo "<span class='success'>✅ Test data cleaned up</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='error'>❌ Lead operations test failed: " . $e->getMessage() . "</span><br>";
    $errors[] = "Lead operations failed";
}

// 6. Laravel configuration
echo "<h2>6. Laravel Configuration</h2>";
if (file_exists('.env')) {
    echo "<span class='success'>✅ .env file exists</span><br>";
} else {
    echo "<span class='error'>❌ .env file missing</span><br>";
    $errors[] = ".env file missing";
}

if (file_exists('public/.htaccess')) {
    echo "<span class='success'>✅ .htaccess file exists</span><br>";
} else {
    echo "<span class='warning'>⚠️ .htaccess file missing</span><br>";
    $warnings[] = ".htaccess file missing";
}

// 7. Clear Laravel cache
echo "<h2>7. Clearing Laravel Cache</h2>";
try {
    if (function_exists('exec')) {
        exec('php artisan config:clear 2>&1', $output1, $return1);
        exec('php artisan cache:clear 2>&1', $output2, $return2);
        exec('php artisan route:clear 2>&1', $output3, $return3);
        
        if ($return1 === 0) echo "<span class='success'>✅ Config cache cleared</span><br>";
        if ($return2 === 0) echo "<span class='success'>✅ Application cache cleared</span><br>";
        if ($return3 === 0) echo "<span class='success'>✅ Route cache cleared</span><br>";
    } else {
        echo "<span class='warning'>⚠️ exec() function disabled - manual cache clear needed</span><br>";
    }
} catch (Exception $e) {
    echo "<span class='warning'>⚠️ Cache clear failed - may need manual clearing</span><br>";
}

// 8. Final report
echo "<h2>🎯 Final Report</h2>";

if (empty($errors)) {
    echo "<div style='background:#d4edda;padding:15px;border-radius:5px;'>";
    echo "<h3 style='color:green;'>🎉 SUCCESS! Server is ready!</h3>";
    echo "<p>All critical checks passed. Your application should work properly.</p>";
    echo "</div>";
} else {
    echo "<div style='background:#f8d7da;padding:15px;border-radius:5px;'>";
    echo "<h3 style='color:red;'>❌ ERRORS FOUND</h3>";
    echo "<p>Please fix these issues:</p><ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul></div>";
}

if (!empty($warnings)) {
    echo "<div style='background:#fff3cd;padding:15px;border-radius:5px;margin-top:10px;'>";
    echo "<h3 style='color:orange;'>⚠️ WARNINGS</h3>";
    echo "<p>These should be addressed:</p><ul>";
    foreach ($warnings as $warning) {
        echo "<li>$warning</li>";
    }
    echo "</ul></div>";
}

echo "<hr><p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>If all checks passed, test your application</li>";
echo "<li>If errors exist, fix them and run this script again</li>";
echo "<li>Set APP_DEBUG=false in .env for production</li>";
echo "<li>Delete this setup file after successful deployment</li>";
echo "</ol>";

?>