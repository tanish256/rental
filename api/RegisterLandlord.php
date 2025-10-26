<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);  // Don't display errors on the page
ini_set('log_errors', 1);      // Log errors to a file
ini_set('error_log', '/error.log'); // Optional: specify a log file
require '../helpers/config.php'; // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $location = $_POST['location'];
    if (isset($_POST['del'])) {
        $idi = $_POST['id'];
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
    }elseif($_POST['id']){
        $landlord_id = $_POST['id'];
        try {
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = "UPDATE landlord SET name = :name, contact = :contact, email = :email, location = :location WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            
            $stmt->execute([
                ':id' => $_POST['id'],
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