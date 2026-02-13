<?php
echo "<h2>🔍 Server Diagnostic Report</h2>";

// 1. Check PHP Version
echo "<h3>1. PHP Version</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Required: 8.1+ " . (version_compare(phpversion(), '8.1.0', '>=') ? "✅" : "❌") . "<br><br>";

// 2. Check Database Connection
echo "<h3>2. Database Connection</h3>";
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=u259894078_Kwikster232", "u259894078_Kwikster231", "Copy@75970");
    echo "Database Connection: ✅ Success<br>";
    
    // Check leads table
    $stmt = $pdo->query("SHOW TABLES LIKE 'leads'");
    echo "Leads Table: " . ($stmt->rowCount() > 0 ? "✅ Exists" : "❌ Missing") . "<br>";
    
} catch (Exception $e) {
    echo "Database Connection: ❌ Failed - " . $e->getMessage() . "<br>";
}
echo "<br>";

// 3. Check File Permissions
echo "<h3>3. File Permissions</h3>";
$dirs = ['storage', 'bootstrap/cache', 'public/uploads'];
foreach ($dirs as $dir) {
    if (file_exists($dir)) {
        echo "$dir: " . (is_writable($dir) ? "✅ Writable" : "❌ Not Writable") . "<br>";
    } else {
        echo "$dir: ❌ Does not exist<br>";
    }
}
echo "<br>";

// 4. Check Required Extensions
echo "<h3>4. PHP Extensions</h3>";
$extensions = ['pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'zip'];
foreach ($extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✅" : "❌") . "<br>";
}
echo "<br>";

// 5. Test Lead Creation
echo "<h3>5. Lead Creation Test</h3>";
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=u259894078_Kwikster232", "u259894078_Kwikster231", "Copy@75970");
    
    // Try to insert test lead
    $stmt = $pdo->prepare("INSERT INTO leads (name, number, role, condition_status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $result = $stmt->execute(['Server Test', '9876543210', 'Test', '']);
    
    if ($result) {
        echo "Lead Insert: ✅ Success<br>";
        // Clean up
        $pdo->exec("DELETE FROM leads WHERE name = 'Server Test' AND number = '9876543210'");
        echo "Cleanup: ✅ Done<br>";
    } else {
        echo "Lead Insert: ❌ Failed<br>";
    }
} catch (Exception $e) {
    echo "Lead Insert: ❌ Error - " . $e->getMessage() . "<br>";
}

echo "<br><h3>6. Server Info</h3>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current Directory: " . getcwd() . "<br>";
echo "Script Path: " . __FILE__ . "<br>";

?>