<?php
// Test database connection
$host = '127.0.0.1';
$dbname = 'u259894078_Kwikster232';
$username = 'u259894078_Kwikster231';
$password = 'Copy@75970';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!\n";
    
    // Test leads table
    $stmt = $pdo->query("SHOW TABLES LIKE 'leads'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Leads table exists!\n";
        
        // Test insert
        $testStmt = $pdo->prepare("INSERT INTO leads (name, number, role, condition_status, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $result = $testStmt->execute(['Test Lead', '1234567890', 'Test Role', '']);
        
        if ($result) {
            echo "✅ Test lead insertion successful!\n";
            
            // Clean up test data
            $pdo->exec("DELETE FROM leads WHERE name = 'Test Lead' AND number = '1234567890'");
            echo "✅ Test data cleaned up!\n";
        } else {
            echo "❌ Test lead insertion failed!\n";
        }
    } else {
        echo "❌ Leads table does not exist!\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}
?>