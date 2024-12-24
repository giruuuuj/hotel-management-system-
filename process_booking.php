<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', 'booking_errors.log');

require_once 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Log received data
        error_log("Received booking request: " . json_encode($_POST));

        // Get and validate form data
        $room_type = filter_input(INPUT_POST, 'room_type', FILTER_SANITIZE_STRING);
        $guest_name = filter_input(INPUT_POST, 'guest_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        if (!$room_type || !$guest_name || !$email) {
            throw new Exception("Missing required fields");
        }

        // Process booking
        $result = processBooking($room_type, $guest_name, $email);
        
        // Log success
        error_log("Booking processed successfully: " . json_encode($result));
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Booking successful',
            'data' => $result
        ]);

    } catch (Exception $e) {
        error_log("Booking error: " . $e->getMessage());
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
}

function processBooking($room_type, $guest_name, $email) {
    global $conn;
    
    // Start transaction
    $conn->beginTransaction();
    
    try {
        // Your booking logic here
        // ...
        
        $conn->commit();
        return ['booking_id' => $conn->lastInsertId()];
    } catch (Exception $e) {
        $conn->rollBack();
        throw $e;
    }
}
?> 