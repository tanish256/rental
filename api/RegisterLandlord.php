<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);  // Don't display errors on the page
ini_set('log_errors', 1);      // Log errors to a file
ini_set('error_log', '/error.log'); // Optional: specify a log file
require '../helpers/config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $contact = $data['contact'];
    $email = $data['email'];
    $location = $data['location'];
    if (isset($data['del'])) {
        $idi = $data['id'];
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqld = "DELETE FROM landlord WHERE id = :id";
            $stmtd = $pdo->prepare($sqld);
            $stmtd->bindParam(':id', $idi, PDO::PARAM_INT);
            $stmtd->execute();
            echo json_encode(["message" => "delete initiated"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }elseif($data['id']){
        //$rooms =$data['rooms'];
        $landlord_id = $data['id'];
        $rooms_count = isset($data['rooms']) ? intval($data['rooms']) : 0;
        if ($rooms_count >= 1) {
                $insert_query = "INSERT INTO rooms (landlord,location) VALUES (:landlord_id,:location)";
                $insert_stmt = $pdo->prepare($insert_query);
    
                for ($i = 0; $i < $rooms_count; $i++) {
                    $insert_stmt->execute([
                        'location' =>$location,
                        'landlord_id' => $landlord_id
                    ]);
                }
                echo "$rooms_count rooms added for landlord ID $landlord_id.";
                if (empty(getBalanceLandlord($landlord_id,date("M"),date("Y"))[0])) {
                    //balance to date already exists
                    try {
                        //code...
                        $daten =date("Y");
                        $monthn =date("M");
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $insert_query = "INSERT INTO balances (`landlord`, `month`, `year`) VALUES (:landlord_id, :month, :year)";
                        $insertb_stmt = $pdo->prepare($insert_query);
                        $insertb_stmt->bindParam(':landlord_id', $landlord_id, PDO::PARAM_INT);
                        $insertb_stmt->bindParam(':month', $monthn, PDO::PARAM_INT);
                        $insertb_stmt->bindParam(':year', $daten, PDO::PARAM_INT);
                        $insertb_stmt->execute();
                        echo json_encode(["message" => "balance added"]);
                    } catch (PDOException $e) {
                        echo json_encode(["error" => $e->getMessage()]);
                    }
                }else{
                    echo "balance exists";
                    
                }

        }
        echo json_encode(["message" => "editing landlord"]);
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "UPDATE landlord SET name = :name, contact = :contact, email = :email, location = :location WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':id' => $data['id'],
                ':name' => $name,
                ':contact' => $contact,
                ':email' => $email,
                ':location' => $location
            ]);
            
            echo json_encode(["message" => "Landlord updated successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }else{
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "INSERT INTO landlord (`name`, `date onboarded`, contact, email, location) VALUES (:name, CURDATE(), :contact, :email, :location)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':name' => $name,
                ':contact' => $contact,
                ':email' => $email,
                ':location' => $location
            ]);
            
            echo json_encode(["message" => "Landlord added successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    
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
    return $transactions ?: [];
}
?>