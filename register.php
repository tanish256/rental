<?php
// Start the session
session_start();
if (!$_SESSION['role'] == 'admin') {
    header("Location: login.php");
  } 

// Include the database configuration file
require 'config.php';

try {
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the username and password from the form
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];
        $name = $_POST['name'];
        $role = $_POST['role']; // Optional: Add a role field for registration (e.g., 'user' or 'admin')

        // Validate input data (simple example, you may want to improve this)
        if (empty($inputUsername) || empty($inputPassword) || empty($role)) {
            echo "Please fill in all the fields.";
            exit;
        }

        // Check if the username already exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :username");
        $stmt->execute(['username' => $inputUsername]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo "Username already taken!";
            exit;
        }

        // Hash the password before storing it
        $hashedPassword = password_hash($inputPassword, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (`user_name`, `pass`, `role`, `name`) VALUES (:username, :password_hash, :role, :name)");
        $stmt->execute([
            'username' => $inputUsername,
            'password_hash' => $hashedPassword,
            'name' => $name,
            'role' => $role
        ]);

        // Optionally, set session variables for the newly registered user
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $inputUsername;
        $_SESSION['role'] = $role;

        // Redirect to the appropriate dashboard after registration
        if ($_SESSION['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    }
} catch (PDOException $e) {
    // Handle any errors with the database connection
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h2>Add User</h2>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="password">Full Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="role">Role (admin/user):</label>
        <input type="text" id="role" name="role" required><br><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
