<?php
header('Content-Type: application/json');

try {
    // Connect to database
    $pdo = new PDO("mysql:host=localhost;dbname=hotel_management", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Log received data
    $received_data = file_get_contents('php://input');
    error_log("Received data: " . $received_data);

    // Process form data
    $room_type = $_POST['room_type'] ?? null;
    $guest_name = $_POST['guest_name'] ?? null;
    $email = $_POST['email'] ?? null;

    // Validate data
    if (!$room_type || !$guest_name || !$email) {
        throw new Exception("Missing required fields");
    }

    // Test database insert
    $stmt = $pdo->prepare("INSERT INTO reservations (room_id, guest_name, email) VALUES (1, ?, ?)");
    $stmt->execute([$guest_name, $email]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Test booking successful',
        'data' => [
            'room_type' => $room_type,
            'guest_name' => $guest_name,
            'email' => $email
        ]
    ]);

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?> 