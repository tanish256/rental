<?php
require 'config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $amount = isset($data['amount']) ? intval($data['amount']) : 0;
    $location = $data['location'];
    $condition = $data['condition'];

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "UPDATE rooms SET amount = :amount, `roomcondition` = :condition, location = :location WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':id' => $data['id'],
        ':amount' => $amount,
        ':condition' => $condition,
        ':location' => $location
    ]);
    
    echo json_encode(["message" => "Room updated successfully."]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
}