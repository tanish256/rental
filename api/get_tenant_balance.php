<?php
require '../helpers/config.php';

header('Content-Type: application/json');
$current_month = date('M');
$current_year = date('Y');
if (isset($_GET['tenant_id'])) {
    $tenant_id = intval($_GET['tenant_id']);
    
    try {
        // Get total balance
        $sql = "SELECT *
                FROM balances 
                WHERE tenant = ? and month = ? and year = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$tenant_id,$current_month,$current_year]);
        $balance_data = $stmt->fetch();
        
        
        echo json_encode($balance_data);
        
    } catch (Exception $e) {
        echo json_encode(['total_balance' => 0]);
    }
} else {
    echo json_encode(['total_balance' => 0]);
}
?>