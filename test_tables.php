<?php
include_once 'config/database.php';

// Create database instance
$database = new Database();
$conn = $database->connect();

if($conn) {
    try {
        // Test rooms table
        $query = "SELECT * FROM rooms LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        echo "✅ Rooms table accessible<br>";

        // Test reservations table
        $query = "SELECT * FROM reservations LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        echo "✅ Reservations table accessible<br>";

        // Test contact_messages table
        $query = "SELECT * FROM contact_messages LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        echo "✅ Contact messages table accessible<br>";

    } catch(PDOException $e) {
        echo "❌ Table Error: " . $e->getMessage();
    }
} else {
    echo "❌ Database connection failed";
}
?> 