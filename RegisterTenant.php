<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);  // Don't display errors on the page
ini_set('log_errors', 1);      // Log errors to a file
ini_set('error_log', '/error.log'); // Optional: specify a log file
require 'config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $contact = $data['contact'];
    $room_id = isset($data['room']) ? intval($data['room']) : 0;
    if (isset($data['del'])) {
        $idi = $data['id'];
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sqld = "DELETE FROM tenants WHERE id = :id";
            $stmtd = $pdo->prepare($sqld);
            $stmtd->bindParam(':id', $idi, PDO::PARAM_INT);
            $stmtd->execute();
            echo json_encode(["message" => "delete initiated"]);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }elseif (isset($data['id'])) {
        if ($data['room']) {
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "UPDATE tenants SET name = :name, contact = :contact, room_id=:room WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([
                    ':id' => $data['id'],
                    ':name' => $name,
                    ':room' => $room_id,
                    ':contact' => $contact

                ]);
                
                echo json_encode(["message" => "Tenant updated successfully."]);
            } catch (PDOException $e) {
                echo json_encode(["error" => $e->getMessage()]);
            }
        }else{
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = "UPDATE tenants SET name = :name, contact = :contact WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                
                $stmt->execute([
                    ':id' => $data['id'],
                    ':name' => $name,
                    ':contact' => $contact
                ]);
                
                echo json_encode(["message" => "Tenant updated successfully."]);
            } catch (PDOException $e) {
                echo json_encode(["error" => $e->getMessage()]);
            }
        }
    }else{
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "INSERT INTO tenants (`name`, `date_onboarded`, contact, room_id) VALUES (:name, CURDATE(), :contact, :room)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':name' => $name,
                ':contact' => $contact,
                ':room' => $room_id
            ]);
            $tenant_id = $pdo->lastInsertId();
            if (empty(getBalance($tenant_id,date("M"),date("Y"))[0])) {
                //balance to date already exists
                try {
                    //code...
                    $daten =date("Y");
                    $monthn =date("M");
                    $datenumber=date("j");
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_query = "INSERT INTO balances (`tenant`, `month`, `year`) VALUES (:tenant_id, :month, :year)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $rooms_query = "SELECT * FROM rooms where id = :room_id";
                    $rooms_stmt = $pdo->prepare($rooms_query);
                    $rooms_stmt->execute([
                        ':room_id' => $room_id
                    ]);
                    $rent_due = $rooms_stmt->fetch(PDO::FETCH_ASSOC)['amount'];
                if ($datenumber<=15) {
                    $insert_query = "INSERT INTO balances (`tenant`, `month`, `year`, `balance_due`,`total_balance`) VALUES (:tenant_id, :month, :year, :balance_due, balance_due)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $insertb_stmt->bindParam(':balance_due', $rent_due, PDO::PARAM_INT);


                    # code...
                }else{
                    $insert_query = "INSERT INTO balances (`tenant`, `month`, `year`) VALUES (:tenant_id, :month, :year)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                }
                
              
                    $insertb_stmt->bindParam(':tenant_id', $tenant_id, PDO::PARAM_INT);
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
            echo json_encode(["message" => "Tenant added successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
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