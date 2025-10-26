<?php

include_once '../helpers/config.php';
$year = date('Y');
header('Content-Type: application/json');

$balances = getYearlyBalances(2025); // Fetch data for the year

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$data = [];
if (isset($_GET['MIG'])) {
    foreach ($months as $month) {
        $totalBalance = $balances[$month]['total_balance_due'];
    
        // If total_balance is NULL, store null, else convert to millions
        $data[] = $totalBalance === 0 ? null : $totalBalance / 1000000;
    }
    echo json_encode($data);
    exit;
}
if (isset($_GET['MCP'])) {
    foreach ($months as $month) {
        $totalBalance = $balances[$month]['total_balance_due']+$balances[$month]['total_balance_bf']-$balances[$month]['total_balance'];
    
        // If total_balance is NULL, store null, else convert to millions
        $data[] = $totalBalance === 0 ? null : $totalBalance / 1000000;
    }
    echo json_encode($data);
    exit;
}



function getYearlyBalances($year) {
    global $pdo;
    try {
        $trans_query = "SELECT 
                            month, 
                        SUM(balance_bf) AS total_balance_bf,
                        SUM(balance_due) AS total_balance_due,
                        SUM(total_balance) AS total_balance
                        FROM balances 
                        WHERE tenant IS NOT NULL AND year = :year
                        GROUP BY month
                        ORDER BY FIELD(month, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                               'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec')";

        $trans_stmt = $pdo->prepare($trans_query);
        $trans_stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $trans_stmt->execute();
        $balances = $trans_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Initialize a result array for all 12 months with default zero values
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                   'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $yearlyBalances = [];

        foreach ($months as $month) {
            $yearlyBalances[$month] = [
                'total_balance_bf' => 0,
                'total_balance_due' => 0,
                'total_balance' => 0
            ];
        }

        // Populate the result array with actual values from the database
        foreach ($balances as $balance) {
            $month = $balance['month'];
            $yearlyBalances[$month] = [
                'total_balance_bf' => $balance['total_balance_bf'] ?: 0,
                'total_balance_due' => $balance['total_balance_due'] ?: 0,
                'total_balance' => $balance['total_balance'] ?: 0
            ];
        }

        return $yearlyBalances;

    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        return [];
    }
}
