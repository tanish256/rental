<?php
// Start the session
require 'config.php';
session_start();

try {
    // Create a PDO connection
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the username and password from the form
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];

        // Prepare the SQL statement to get the user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :username");
        $stmt->execute(['username' => $inputUsername]);

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and verify the password
        if ($user && password_verify($inputPassword, $user['password_hash'])) {
            // Set session variables based on the username and role
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the appropriate page based on the role
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            // Invalid login
            echo "Invalid username or password!";
        }
    }
} catch (PDOException $e) {
    // Handle any errors with the database connection
    echo "Error: " . $e->getMessage();
}
?>
