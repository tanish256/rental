<?php
require 'config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $contact = $data['contact'];
    $room_id = isset($data['room']) ? intval($data['room']) : 0;
    if (isset($data['id'])) {
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
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_query = "INSERT INTO balances (`tenant`, `month`, `year`) VALUES (:landlord_id, :month, :year)";
                    $insertb_stmt = $pdo->prepare($insert_query);
                    $insertb_stmt->bindParam(':landlord_id', $tenant_id, PDO::PARAM_INT);
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