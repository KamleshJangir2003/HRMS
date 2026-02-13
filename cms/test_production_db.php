<?php
// Production Database Connection Test
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Testing Database Connection...\n";
echo "Host: " . $_ENV['DB_HOST'] . "\n";
echo "Database: " . $_ENV['DB_DATABASE'] . "\n";
echo "Username: " . $_ENV['DB_USERNAME'] . "\n";

try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']};charset=utf8mb4",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 30,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_PERSISTENT => false,
        ]
    );
    
    echo "✅ Database connection successful!\n";
    
    // Test leads table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM leads");
    $result = $stmt->fetch();
    echo "✅ Leads table accessible. Total leads: " . $result['count'] . "\n";
    
    // Test insert capability
    $testData = [
        'name' => 'Test Lead ' . date('Y-m-d H:i:s'),
        'number' => '9999999999',
        'role' => 'Test Role',
        'condition_status' => ''
    ];
    
    $stmt = $pdo->prepare("INSERT INTO leads (name, number, role, condition_status) VALUES (?, ?, ?, ?)");
    $stmt->execute(array_values($testData));
    
    $insertId = $pdo->lastInsertId();
    echo "✅ Test insert successful. ID: $insertId\n";
    
    // Clean up test data
    $stmt = $pdo->prepare("DELETE FROM leads WHERE id = ?");
    $stmt->execute([$insertId]);
    echo "✅ Test cleanup successful\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    
    // Common production issues
    if (strpos($e->getMessage(), 'Connection refused') !== false) {
        echo "💡 Suggestion: Check if MySQL service is running\n";
    } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "💡 Suggestion: Check database credentials\n";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "💡 Suggestion: Check if database exists\n";
    }
}