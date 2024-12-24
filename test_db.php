<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost';
$dbname = 'hotel_management';
$username = 'root';  // default XAMPP username
$password = '';      // default XAMPP password

try {
    // Attempt to connect
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!";
    
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?> 