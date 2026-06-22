<?php
/**
 * Database Connection Configuration - Mambo Hardware
 * Engine: PDO (PHP Data Objects)
 * Environment: Dual-Mode (Local Development & Vercel Production)
 */

// 1. Setup connection coordinates dynamically
if (getenv('DB_HOST')) {
    // Live Production Settings (Vercel + Aiven.io Cloud MySQL)
    $host    = getenv('DB_HOST');
    $db      = getenv('DB_NAME');
    $user    = getenv('DB_USER');
    $pass    = getenv('DB_PASSWORD');
    $port    = getenv('DB_PORT');
} else {
    // Local Development Settings (Your local machine)
    $host    = '127.0.0.1'; 
    $db      = 'mellar_outdoors'; 
    $user    = 'root';           
    $pass    = '';               
    $port    = '3306';           
}

$charset = 'utf8mb4'; // Universal character set for security and emojis

// 2. Build the Data Source Name (DSN) including the dynamic port
$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";

// 3. Establish strict execution rules
$options = [
    // Throws PDOExceptions on errors instead of failing silently
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    
    // Forces database arrays to return columns indexed by name natively
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       
    
    // Disables emulated prepared statements to force true compiled SQL queries (Crucial SQLi protection)
    PDO::ATTR_EMULATE_PREPARES   => false,                  
];

try {
    // 4. Initialize the global PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (\PDOException $e) {
    // 5. Secure Error Management
    error_log("Database connection error: " . $e->getMessage());
    
    // Prevent sensitive system paths or cloud endpoints from spilling out to the browser
    if (!headers_sent()) {
        header('Content-Type: application/json', true, 500);
    }
    echo json_encode(['error' => 'Database service temporarily unavailable.']);
    exit;
}