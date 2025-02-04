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



// Fetch all rooms
$rooms_query = "SELECT * FROM rooms";
$rooms_stmt = $pdo->prepare($rooms_query);
$rooms_stmt->execute();
$rooms = $rooms_stmt->fetchAll(PDO::FETCH_ASSOC);

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


// Function to get Tenant by ID
function getTenant($tid, $tenants) {
    foreach ($tenants as $tenant) {
        if ($tenant['id'] == $tid) {
            global $rooms;
            global $landlords;
            $room =getRoom($tenant['room_id'], $rooms);
            $landlord= getLandlord($room['landlord'],$landlords);
            $tenant['landlord']=$landlord['name'];
            $tenant['location']=$room['location'];
            return json_encode($tenant);
        }
    }
    return null;
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
    // If 'tid' is not provided, return a 400 Bad Request
   // http_response_code(400);
   // echo json_encode(['error' => 'Tenant ID (tid) is required']);
}

?>
