<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once '../config/db_connect.php';

// Set headers
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $room_type = $_POST['room_type'] ?? '';
        $guest_name = $_POST['guest_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $check_in = $_POST['check_in'] ?? '';
        $check_out = $_POST['check_out'] ?? '';
        $guests_count = $_POST['guests_count'] ?? 1;

        // Validate required fields
        if (empty($room_type) || empty($guest_name) || empty($email) || empty($check_in) || empty($check_out)) {
            throw new Exception("All required fields must be filled out");
        }

        // Get available room of requested type
        $stmt = $conn->prepare("SELECT room_id FROM rooms WHERE room_type = ? AND status = 'available' LIMIT 1");
        $stmt->execute([$room_type]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            throw new Exception("No available rooms of this type");
        }

        // Start transaction
        $conn->beginTransaction();

        try {
            // Insert reservation
            $stmt = $conn->prepare("
                INSERT INTO reservations 
                (room_id, guest_name, email, phone, check_in, check_out, guests_count, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
            ");
            
            $stmt->execute([
                $room['room_id'],
                $guest_name,
                $email,
                $phone,
                $check_in,
                $check_out,
                $guests_count
            ]);

            // Update room status
            $stmt = $conn->prepare("UPDATE rooms SET status = 'booked' WHERE room_id = ?");
            $stmt->execute([$room['room_id']]);

            // Commit transaction
            $conn->commit();

            echo json_encode([
                'status' => 'success',
                'message' => 'Booking successful!',
                'booking_id' => $conn->lastInsertId()
            ]);

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollBack();
            throw $e;
        }

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?> 