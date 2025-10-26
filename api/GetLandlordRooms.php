<?php
header('Content-Type: application/json');
require_once '../helpers/config.php';

// ---- FETCH ROOMS BY LANDLORD ID ----
if (isset($_GET['landlord_id'])) {
    $landlordId = intval($_GET['landlord_id']);

    $query = "
        SELECT r.*, t.id AS tenant_id, l.name AS landlord_name
        FROM rooms r
        LEFT JOIN tenants t ON r.id = t.room_id
        LEFT JOIN landlord l ON r.landlord = l.id
        WHERE r.landlord = :landlord
    ";


    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':landlord', $landlordId, PDO::PARAM_INT);
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "rooms" => $rooms
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Missing landlord_id parameter"
    ]);
}
?>
