<?php
require_once 'vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "🔍 Testing Lead Upload Functionality\n";
echo "=====================================\n";

try {
    // Test database connection
    $connection = $capsule->getConnection();
    $connection->getPdo();
    echo "✅ Database connection established\n";
    
    // Check if leads table exists
    $tables = $connection->select("SHOW TABLES LIKE 'leads'");
    if (empty($tables)) {
        echo "❌ Leads table does not exist!\n";
        exit;
    }
    echo "✅ Leads table exists\n";
    
    // Test lead creation
    $testData = [
        'name' => 'Debug Test Lead',
        'number' => '9999999999',
        'role' => 'Test Role',
        'condition_status' => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Check for duplicates
    $existing = $connection->table('leads')->where('number', $testData['number'])->first();
    if ($existing) {
        echo "⚠️  Test lead already exists, deleting...\n";
        $connection->table('leads')->where('number', $testData['number'])->delete();
    }
    
    // Insert test lead
    $result = $connection->table('leads')->insert($testData);
    
    if ($result) {
        echo "✅ Test lead inserted successfully!\n";
        
        // Verify insertion
        $inserted = $connection->table('leads')->where('number', $testData['number'])->first();
        if ($inserted) {
            echo "✅ Test lead verified in database\n";
            echo "   ID: {$inserted->id}\n";
            echo "   Name: {$inserted->name}\n";
            echo "   Number: {$inserted->number}\n";
        }
        
        // Clean up
        $connection->table('leads')->where('number', $testData['number'])->delete();
        echo "✅ Test data cleaned up\n";
    } else {
        echo "❌ Test lead insertion failed!\n";
    }
    
    // Test file upload directory
    $uploadDir = public_path('uploads');
    if (!file_exists($uploadDir)) {
        echo "⚠️  Upload directory does not exist, creating...\n";
        mkdir($uploadDir, 0755, true);
    }
    
    if (is_writable($uploadDir)) {
        echo "✅ Upload directory is writable\n";
    } else {
        echo "❌ Upload directory is not writable!\n";
    }
    
    echo "\n🎉 All tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>