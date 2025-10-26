<?php
require '../helpers/config.php'; // Include your database connection
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Get input data
$roomId = isset($_POST['id']) ? intval($_POST['id']) : null;
$amount = isset($_POST['amount']) ? intval($_POST['amount']) : null;
$location = $_POST['location'] ?? null;
$condition = $_POST['condition'] ?? null;

if (!$roomId) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "Room ID is required"]);
    exit;
}

// Delete room if 'del' is set
if (!empty($_POST['del'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :room_id");
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            http_response_code(200); // OK
            echo json_encode(["message" => "Room deleted successfully."]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["error" => "Error deleting room."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit;
}


try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE rooms SET amount = :amount, roomcondition = :condition, location = :location WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $roomId,
        ':amount' => $amount,
        ':condition' => $condition,
        ':location' => $location
    ]);

    http_response_code(200); // OK
    echo json_encode(["message" => "Room updated successfully."]);

} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["error" => $e->getMessage()]);
}
