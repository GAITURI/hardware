<?php
/**
 * Database Connection Configuration - / Mambo Hardware
 * Engine: PDO (PHP Data Objects)
 */

// 1. Setup connection coordinates
$host    = 'localhost';         // Change to your server hostname if live
$db      = 'mellar_outdoors';    // Your database schema name
$user    = 'root';              // Database username
$pass    = '';                  // Database password
$charset = 'utf8mb4';           // Universal character set for security and emojis

// 2. Build the Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

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
    // For local development, this helps you debug immediately.
    // In live production, replace this with: error_log($e->getMessage()); die("Service temporarily unavailable.");
    error_log("Database connection error: " . $e->getMessage());
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
    header('Content-Type: application/json', true, 500);
    echo json_encode(['error' => 'Database service temporarily unavailable.']);
    exit;
}