<?php
// Test database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=hotel_management", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connection successful!<br>";
} catch(PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}

// Test tables existence
$tables = ['rooms', 'reservations', 'contact_messages', 'newsletter_subscribers'];
foreach ($tables as $table) {
    try {
        $result = $pdo->query("SELECT 1 FROM $table LIMIT 1");
        echo "✅ Table '$table' exists<br>";
    } catch(PDOException $e) {
        echo "❌ Table '$table' not found<br>";
    }
}
?> 