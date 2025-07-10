<?php 
session_start();
if (!isset($_SESSION['loggedin'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);  // Don't display errors on the page
ini_set('log_errors', 1);      // Log errors to a file
ini_set('error_log', '/error.log'); // Optional: specify a log file
require 'config.php';
$year = date("Y");
$month = date("M");

// Load balances for tenants
$balance_query = "SELECT * FROM balances WHERE month = :month AND year = :year AND tenant IS NOT NULL";
$balance_stmt = $pdo->prepare($balance_query);
$balance_stmt->execute([
    ':month' => $month,
    ':year' => $year
]);
$allBalances = $balance_stmt->fetchAll(PDO::FETCH_ASSOC);

// Index balances by tenant ID
$tenantBalances = [];
foreach ($allBalances as $b) {
    $tenantBalances[$b['tenant']] = $b;
}



$sql_rwt = "SELECT *
    FROM rooms 
    WHERE id NOT IN (SELECT room_id FROM tenants WHERE room_id IS NOT NULL)";
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
$total_balance_bfw = 0;
$total_balance_duew = 0;
$total_balance = 0;
// Iterate through the tenants array and sum up the balances
foreach ($tenants as $tenant) {
    $balances = isset($tenantBalances[$tenant['id']]) ? [$tenantBalances[$tenant['id']]] : [[]];
    $balance_bf = isset($balances[0]['balance_bf']) && $balances[0]['balance_bf'] >= 0 ? $balances[0]['balance_bf'] : 0;
    $balance_due = isset($balances[0]['balance_due']) && $balances[0]['balance_due'] >= 0 ? $balances[0]['balance_due'] : 0;
    $balance = isset($balances[0]['total_balance']) && $balances[0]['total_balance'] >= 0 ? $balances[0]['total_balance'] : 0;

    // Add to the running totals
    $total_balance_bfw += $balance_bf;
    $total_balance_duew += $balance_due;
    $total_balance += $balance;
}
function getRoom($room_id, $rooms) {
    foreach ($rooms as $room) {
        if ($room['id'] == $room_id) {
            return $room;
        }
    }
    return null;
}
// Function to get summary of Given Date

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
    global $tenants,$tenantBalances;
    foreach ($tenants as $tenant) {
        if ($tenant['id'] == $tid) {
            global $rooms;
            global $landlords;
            $balances = isset($tenantBalances[$tenant['id']]) ? [$tenantBalances[$tenant['id']]] : [[]];
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
            $tenant['amount']=$room['amount'];
            return json_encode($tenant);
        }
    }
    return null;
}
function getTenantsByLandlord($landlord_id) {
    global $tenants, $rooms, $landlords, $tenantBalances;

    $filtered_tenants = [];

    foreach ($tenants as $tenant) {
        $room = getRoom($tenant['room_id'], $rooms);
        if ($room && $room['landlord'] == $landlord_id) {
            $balances = isset($tenantBalances[$tenant['id']]) ? $tenantBalances[$tenant['id']] : [];

            $tenant['balance_bf'] = isset($balances['balance_bf']) ? $balances['balance_bf'] : 0;
            $tenant['balance_due'] = isset($balances['balance_due']) ? $balances['balance_due'] : 0;
            $tenant['balance'] = isset($balances['total_balance']) ? $balances['total_balance'] : 0;

            $landlord = getLandlord($room['landlord'], $landlords);

            $tenant['landlord'] = $landlord['name'] ?? '';
            $tenant['location'] = $room['location'] ?? '';
            $tenant['amount'] = $room['amount'] ?? 0;

            $filtered_tenants[] = $tenant;
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

function getcomission($landlord,$paid,$no_tenants) : int {
    $fixed =0;
    $percentage =1;
    $payout =2;
    $per_room=3;

    if($paid<=0){
               return 0; // Unknown commission type
    }else{
        switch ($landlord['commission_type']) {
        case $fixed:
            return intval($landlord['commission']);
        case $percentage:
            return intval($paid * $landlord['commission'] / 100);
        case $payout:
            return max(0, $paid - $landlord['commission']);
        case $per_room:
            return $no_tenants*$landlord['commission'];
        default:
            return 0; // Unknown commission type
    }

    }
}


//never touch it handles monthly balances
$file = 'year.txt';
$currentYearMonth = date('Y-M');
if (file_exists($file)) {
    // Read the last year-month from the file
    $lastYearMonth = file_get_contents($file);
    list($lastYear, $lastMonth) = explode('-', $lastYearMonth);
    if ($lastYearMonth !== $currentYearMonth) {
        file_put_contents($file, $currentYearMonth);
        //loop through tenants
        foreach ($tenants as $tenant) {
            $tenantId =$tenant['id'];
            $room = getRoom($tenant['room_id'], $rooms);
            $payment = $room['amount'];
            $balance =getBalance($tenant['id'],$lastMonth,$lastYear)[0];
            
            if(empty($balance)){
                try {
                    //code...
                    $balance_duenm =$payment;
                    $daten =date("Y");
                    $monthn =date("M");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_query = "INSERT INTO balances (`tenant`, `month`, `year`,`balance_due`,`total_balance`) VALUES (:tenant_id, :month, :year, :balance_due, :total_balance)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $insertb_stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':month', $monthn, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':year', $daten, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':balance_due', $balance_duenm, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':total_balance', $balance_duenm, PDO::PARAM_INT);
                    $insertb_stmt->execute();
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
                //echo "empty";
               
            }else{
                //echo "not empty";
                try {
                    //echo "new month with previous blance";
                    //code...
                    $balance_bfnm =$balance['total_balance'];
                    $balance_duenm =$payment;
                    $total_balancenm =$balance_bfnm+$balance_duenm;
                    $datenm =date("Y");
                    $monthnm =date("M");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_query = "INSERT INTO balances (`tenant`, `month`, `year`,`balance_bf`,`balance_due`,`total_balance`) VALUES (:tenant_id, :month, :year, :balance_bf, :balance_due, :total_balance)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $insertb_stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':month', $monthnm, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':year', $datenm, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':balance_due', $balance_duenm, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':balance_bf', $balance_bfnm, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':total_balance', $total_balancenm, PDO::PARAM_INT);
                    $insertb_stmt->execute();
                    //echo json_encode(["message" => "balance added"]);
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }

            }
        }
        foreach ($landlords as $landlord) {
            $balances = getBalanceLandlord($landlord['id'],$lastMonth,$lastYear)[0];
            if (empty($balances)) {
                try {
                    //code...
                    $total_balance_due = 0;
                    $landlordID=$landlord['id'];
                    $tenantsl = getTenantsByLandlord($landlord['id']);
                    // If the function returns a JSON-encoded string, you need to decode it
                    $tenantst = json_decode($tenantsl, true);
                    foreach ($tenantst as $tenant) {
                        $total_balance_due += isset($tenant['balance_due']) ? $tenant['balance_due'] : 0;
                    }
                    $daten =date("Y");
                    $monthn =date("M");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_query = "INSERT INTO balances (`landlord`, `month`, `year`,`balance_due`,`total_balance`) VALUES (:tenant_id, :month, :year, :balance_due, :total_balance)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $insertb_stmt->bindParam(':tenant_id', $landlordID, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':month', $monthn, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':year', $daten, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':balance_due', $total_balance_due, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':total_balance', $total_balance_due, PDO::PARAM_INT);
                    $insertb_stmt->execute();
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
            }else{
                try {
                    //code...
                    $total_balance_bf=$balances['total_balance'];
                    $total_balance_due = 0;
                    $landlordID=$landlord['id'];
                    $tenantsl = getTenantsByLandlord($landlord['id']);
                    // If the function returns a JSON-encoded string, you need to decode it
                    $tenantst = json_decode($tenantsl, true);
                    foreach ($tenantst as $tenant) {
                        $total_balance_due += isset($tenant['balance_due']) ? $tenant['balance_due'] : 0;
                    }
                    $total_balance_l=$total_balance_due+$total_balance_bf;
                    $daten =date("Y");
                    $monthn =date("M");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_query = "INSERT INTO balances (`landlord`, `month`, `year`,`balance_due`,`total_balance`,`balance_bf`) VALUES (:tenant_id, :month, :year, :balance_due, :total_balance, :balance_bf)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $insertb_stmt->bindParam(':tenant_id', $landlordID, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':month', $monthn, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':year', $daten, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':balance_due', $total_balance_due, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':balance_bf', $total_balance_bf, PDO::PARAM_INT);
                    $insertb_stmt->bindParam(':total_balance', $total_balance_l, PDO::PARAM_INT);
                    $insertb_stmt->execute();
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
            }
        }
        }else{
            //echo "same motnh";
        }
        
    } else {
        file_put_contents($file, $currentYearMonth);
        //echo "It's still the same year-month.";
    }
?>
