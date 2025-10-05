<?php
require 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['room_id'])) {
    $room_id = intval($_POST['room_id']);
    
    // Verify confirmation
    if (!isset($_POST['confirm']) || $_POST['confirm'] !== 'DELETE') {
        echo json_encode(['success' => false, 'message' => 'Confirmation required']);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        // Check if room exists
        $sql = "SELECT * FROM rooms WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$room_id]);
        $room = $stmt->fetch();
        
        if (!$room) {
            echo json_encode(['success' => false, 'message' => 'Room not found']);
            exit;
        }
        
        // Check if room has active tenants
        $sql = "SELECT COUNT(*) as tenant_count FROM tenants WHERE room_id = ? AND status = 'active'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$room_id]);
        $tenant_count = $stmt->fetch()['tenant_count'];
        
        if ($tenant_count > 0) {
            // Unassign tenants from this room
            $sql = "UPDATE tenants SET room_id = NULL WHERE room_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$room_id]);
        }
        
        // Delete the room
        $sql = "DELETE FROM rooms WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$room_id]);
        
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Room deleted successfully']);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Error deleting room: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>