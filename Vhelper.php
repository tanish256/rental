<?php 
require 'config.php';
$year = date("Y");
$month = date("M");

// Fetch Summary
$summary_query = "SELECT * FROM accs_summary";
$summary_stmt = $pdo->prepare($summary_query);
$summary_stmt->execute();
$summarys = $summary_stmt->fetchAll(PDO::FETCH_ASSOC);
$Tsummary =getSummary($month, $year,$summarys);


// Fetch Transactions
$sql_rwt = "SELECT * FROM rooms WHERE id NOT IN (SELECT room_id FROM tenants)";
$stmt_rwt = $pdo->prepare($sql_rwt);
$stmt_rwt->execute();
$roomsWithoutTenant = $stmt_rwt->fetchAll(PDO::FETCH_ASSOC);



// Fetch all rooms
$rooms_query = "SELECT * FROM rooms";
$rooms_stmt = $pdo->prepare($rooms_query);
$rooms_stmt->execute();
$rooms = $rooms_stmt->fetchAll(PDO::FETCH_ASSOC);

$vacant_query = "SELECT * FROM rooms where status = 0";
$vacant_stmt = $pdo->prepare($vacant_query);
$vacant_stmt->execute();
$vacant = $vacant_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all landlords
$landlords_query = "SELECT * FROM landlord";
$landlords_stmt = $pdo->prepare($landlords_query);
$landlords_stmt->execute();
$landlords = $landlords_stmt->fetchAll(PDO::FETCH_ASSOC);
$tlandlords = count($landlords);
// Fetch all tenants
$tenants_query = "SELECT * FROM tenants";
$tenants_stmt = $pdo->prepare($tenants_query);
$tenants_stmt->execute();
$tenants = $tenants_stmt->fetchAll(PDO::FETCH_ASSOC);
$ttenants = count($tenants);

// Function to get room information by ID
function getRoom($room_id, $rooms) {
    foreach ($rooms as $room) {
        if ($room['id'] == $room_id) {
            return $room;
        }
    }
    return null;
}
// Function to get summary of Given Date
function getSummary($month, $year, $summarys) {
    foreach ($summarys as $summary) {
        if ($summary['f_year'] == $year && $summary['f_month'] == $month) {
            return $summary;
        }
    }
    return null;
}

// Function to get landlord by ID
function getLandlord($landlord_id, $landlords) {
    foreach ($landlords as $landlord) {
        if ($landlord['id'] == $landlord_id) {
            return $landlord;
        }
    }
    return null;
}

// Function to get transactions by tenant ID
function getTransactions($tenant_id) {
    global $pdo;
    $trans_query = "SELECT * FROM rooms_payment WHERE tenant = :tenant_id";
    $trans_stmt = $pdo->prepare($trans_query);
    $trans_stmt->bindParam(':tenant_id', $tenant_id, PDO::PARAM_INT);
    $trans_stmt->execute();
    $transactions = $trans_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $transactions ?: []; // Return an empty array if no transactions are found
}
function getBalance($tenant_id, $month, $year) {
    global $pdo;
    // Query to fetch the transactions based on tenant_id, month, and year
    $trans_query = "SELECT * FROM balances WHERE tenant = :tenant_id AND month = :month AND year = :year";
    $trans_stmt = $pdo->prepare($trans_query);
    // Bind the parameters to the query
    $trans_stmt->bindParam(':tenant_id', $tenant_id, PDO::PARAM_INT);
    $trans_stmt->bindParam(':month', $month, PDO::PARAM_INT);
    $trans_stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $trans_stmt->execute();
    $transactions = $trans_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $transactions ?: []; // Return an empty array if no transactions are found
}
function getBalanceLandlord($tenant_id, $month, $year) {
    global $pdo;
    // Query to fetch the transactions based on tenant_id, month, and year
    $trans_query = "SELECT * FROM balances WHERE landlord = :tenant_id AND month = :month AND year = :year";
    $trans_stmt = $pdo->prepare($trans_query);
    // Bind the parameters to the query
    $trans_stmt->bindParam(':tenant_id', $tenant_id, PDO::PARAM_INT);
    $trans_stmt->bindParam(':month', $month, PDO::PARAM_INT);
    $trans_stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $trans_stmt->execute();
    $transactions = $trans_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $transactions ?: []; // Return an empty array if no transactions are found
}


// Function to get Tenant by ID
function getTenant($tid) {
    global $tenants;
    foreach ($tenants as $tenant) {
        if ($tenant['id'] == $tid) {
            global $rooms;
            global $landlords;
            $balances = getBalance($tenant['id'],date("M"),date("Y"));
            $balance_bf = isset($balances[0]['balance_bf']) ? $balances[0]['balance_bf'] : 0;
            $balance_due = isset($balances[0]['balance_due']) ? $balances[0]['balance_due'] : 0;
            $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
            $room =getRoom($tenant['room_id'], $rooms);
            $landlord= getLandlord($room['landlord'],$landlords);
            $tenant['balance_bf'] = $balance_bf;
            $tenant['balance_due'] = $balance_due;
            $tenant['balance'] = $balance;
            $tenant['landlord']=$landlord['name'];
            $tenant['location']=$room['location'];
            return json_encode($tenant);
        }
    }
    return null;
}
function getTenantsByLandlord($landlord_id) {
    global $tenants, $rooms;
    $filtered_tenants = [];

    foreach ($tenants as $tenant) {
        $room = getRoom($tenant['room_id'], $rooms);
        if ($room && $room['landlord'] == $landlord_id) {
            $balances = getBalance($tenant['id'],date("M"),date("Y"));
            $tenant['balance_bf'] = isset($balances[0]['balance_bf']) ? $balances[0]['balance_bf'] : 0;
            $tenant['balance_due'] = isset($balances[0]['balance_due']) ? $balances[0]['balance_due'] : 0;
            $tenant['balance'] = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
            $filtered_tenants[] = json_decode(getTenant($tenant['id']));

        }
    }

    return json_encode($filtered_tenants);
}
if (isset($_GET['blandlord'])) {
    $landlord_id = $_GET['blandlord'];
    $tenants = getTenantsByLandlord($landlord_id);
    echo json_encode(['success' => true, 'data' => json_decode($tenants, true)]);
}
if (isset($_GET['landlord'])) {
    $landlord_id = $_GET['landlord'];
    $landlord = getLandlord($landlord_id,$landlords);
    echo json_encode(['success' => true, 'data' =>$landlord]);
}
if (isset($_GET['room'])) {
    $room_id = $_GET['room'];
    $room_data =getRoom($room_id, $rooms);
    $landlord = getLandlord($room_data['landlord'],$landlords);
    $room_data['landlord_name']=$landlord['name'];
    echo json_encode(['success' => true, 'data' =>$room_data]);
}

if (isset($_GET['tenant'])) {
    $tenant_id = $_GET['tenant']; // Get tenant ID from the query string
    $tenant= getTenant($tenant_id,$tenants);
    if ($tenant) {
        # code...
        $tenant_data = json_decode($tenant, true);  // Decode JSON into an arra
        // Return the success response with tenant data
        echo json_encode([
            'success' => true,
            'data' => $tenant_data
        ]);
    }
} else {
}
function roomHasTenant($pdo, $room_id) {
    try {
        $sql = "SELECT COUNT(*) FROM tenants WHERE room_id = :room_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        // If the count is greater than 0, the room has a tenant
        return $count > 0;

    } catch (PDOException $e) {
        // Handle any errors (e.g., database connection issues)
        echo "Error: " . $e->getMessage();
        return false; 
    }
}
?>
