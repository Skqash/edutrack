<?php
/**
 * Railway Database Connection Checker
 * Run this with: railway run php check-railway-db.php
 */

echo "=== Railway Database Connection Checker ===\n\n";

// Check environment variables
echo "1. Checking Environment Variables:\n";
echo "   DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NOT SET') . "\n";
echo "   DB_HOST: " . (getenv('DB_HOST') ?: 'NOT SET') . "\n";
echo "   DB_PORT: " . (getenv('DB_PORT') ?: 'NOT SET') . "\n";
echo "   DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";
echo "   DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'NOT SET') . "\n";
echo "   DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '***SET***' : 'NOT SET') . "\n\n";

// Check if MySQL variables exist
echo "2. Checking MySQL Service Variables:\n";
echo "   MYSQL_HOST: " . (getenv('MYSQL_HOST') ?: 'NOT SET') . "\n";
echo "   MYSQL_PORT: " . (getenv('MYSQL_PORT') ?: 'NOT SET') . "\n";
echo "   MYSQL_DATABASE: " . (getenv('MYSQL_DATABASE') ?: 'NOT SET') . "\n";
echo "   MYSQL_USER: " . (getenv('MYSQL_USER') ?: 'NOT SET') . "\n";
echo "   MYSQL_PASSWORD: " . (getenv('MYSQL_PASSWORD') ? '***SET***' : 'NOT SET') . "\n\n";

// Try to connect
echo "3. Testing Database Connection:\n";
try {
    $host = getenv('DB_HOST') ?: getenv('MYSQL_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: getenv('MYSQL_PORT') ?: '3306';
    $database = getenv('DB_DATABASE') ?: getenv('MYSQL_DATABASE') ?: 'laravel';
    $username = getenv('DB_USERNAME') ?: getenv('MYSQL_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
    
    echo "   Attempting connection to: $host:$port/$database\n";
    
    $dsn = "mysql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    
    echo "   ✅ Connection successful!\n\n";
    
    // Check if tables exist
    echo "4. Checking for existing tables:\n";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        echo "   Found " . count($tables) . " tables:\n";
        foreach ($tables as $table) {
            echo "   - $table\n";
        }
    } else {
        echo "   No tables found. Migrations need to be run.\n";
    }
    
} catch (PDOException $e) {
    echo "   ❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n=== End of Check ===\n";
