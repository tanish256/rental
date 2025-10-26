<?php
// collect_payment.php
require '../helpers/config.php';
// Ensure data is sent via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $payment = $_POST['payment'];
    $date = $_POST['date'];
    $year = date('Y'); // Get current year
    $month = date('M'); // Get current month
    
    // Sanitize and validate input
    if (isset($_POST['tenant_id']) && isset($payment) && is_numeric($payment) && isset($_POST['date'])) {
        $tenantId = $_POST['tenant_id'];
        // Start transaction to ensure atomicity
        try {
            // Begin the transaction
            $pdo->beginTransaction();
            
            // Step 1: Update the balance for the tenant
            $stmt = $pdo->prepare("UPDATE balances SET total_balance = total_balance - :payment WHERE tenant = :tenant_id AND month = :month AND year = :year");
            $stmt->execute([
                ':payment' => $payment,
                ':tenant_id' => $tenantId,
                ':month' => $month,
                ':year' => $year
            ]);

            // Step 2: Insert the payment transaction into rooms_payment table
            $stmt2 = $pdo->prepare("
                INSERT INTO `rooms_payment` 
                (`id`, `date_paid`, `amount`, `tenant`, `room`, `comission`, `remarks`, `year`, `month`) 
                VALUES 
                (NULL, :date, :amount, :tenant, :room, NULL, NULL, :year, :month)
            ");
            
            // Fetch room and landlord data (assume `getRoom` is available)
            $room = getTenant($tenantId);
            $roomId = $room['room_id'];

            $stmt2->execute([
                ':date' => $date,
                ':amount' => $payment,
                ':tenant' => $tenantId,
                ':room' => $roomId,
                ':year' => $year,
                ':month' => $month
            ]);

            // Commit the transaction
            $pdo->commit();

            // Return success message
            echo json_encode(['status' => 'success', 'message' => 'Payment collected and transaction recorded successfully']);
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $pdo->rollBack();

            // Return error message
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } elseif (isset($_POST['landlord_id']) && isset($payment) && is_numeric($payment)) {
        try {
            // Begin the transaction
            $landlordId = $_POST['landlord_id'];
            $pdo->beginTransaction();
            
            // Step 1: Update the balance for the tenant
            $stmt_l = $pdo->prepare("UPDATE balances SET total_balance = total_balance - :payment WHERE landlord = :landlord_id AND month = :month AND year = :year");
            $stmt_l->execute([
                ':payment' => $payment,
                ':landlord_id' => $landlordId,
                ':month' => $month,
                ':year' => $year
            ]);

            // Step 2: Insert the payment transaction into rooms_payment table
            $stmt2_l = $pdo->prepare("
                INSERT INTO `disburse_landlord` 
                ( `date_paid`, `amount`, `landlord`, `year`, `month`) 
                VALUES 
                (NOW(), :amount, :landlord, :year, :month)
            ");
            
            $stmt2_l->execute([
                ':amount' => $payment,
                ':landlord' => $landlordId,
                ':month' => $month,
                ':year' => $year
            ]);

            // Commit the transaction
            $pdo->commit();

            // Return success message
            echo json_encode(['status' => 'success', 'message' => 'Payment collected and transaction recorded successfully']);
        } catch (PDOException $e) {
            // Rollback the transaction in case of error
            $pdo->rollBack();

            // Return error message
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else{
        // Return error if input is invalid
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}



function getTenant($tid) {
    global $pdo;
    $tenants_query = "SELECT * FROM tenants WHERE id = :tenant_id";
    $tenants_stmt = $pdo->prepare($tenants_query);
    $tenants_stmt->bindParam(':tenant_id', $tid, PDO::PARAM_INT);
    $tenants_stmt->execute();
    $tenant = $tenants_stmt->fetch(PDO::FETCH_ASSOC);  // Fetch one tenant row directly
    return $tenant ? $tenant : null;  // Return the tenant or null if not found
}

?>