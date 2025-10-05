<?php
require 'config.php';

header('Content-Type: application/json');

if (isset($_GET['tenant_id'])) {
    $tenant_id = intval($_GET['tenant_id']);
    
    try {
        // Get last 3 transactions for the tenant
        $sql = "SELECT *
                FROM rooms_payment 
                WHERE tenant = ? 
                ORDER BY id DESC 
                LIMIT 3";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tenant_id]);
        $transactions = $stmt->fetchAll();
        
        echo json_encode($transactions);
        
    } catch (Exception $e) {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>