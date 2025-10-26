<?php
require "../helpers/config.php"; // include your PDO connection

header('Content-Type: application/json');

try {
    // Ensure request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method");
    }

    // Get POST data
    $landlord = $_POST['landlord'] ?? null;
    $location = $_POST['location'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $condition = $_POST['condition'] ?? null;

    // Validate required fields
    if (!$landlord || !$location || !$amount) {
        throw new Exception("Please fill in all required fields");
    }

    // Prepare insert query
    $stmt = $pdo->prepare("
        INSERT INTO rooms (landlord, location, amount, roomcondition)
        VALUES (:landlord, :location, :amount, :condition)
    ");

    $stmt->execute([
        ':landlord' => $landlord,
        ':location' => $location,
        ':amount' => $amount,
        ':condition' => $condition
    ]);

    $roomId = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => "Room registered successfully",
        'room_id' => $roomId
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
