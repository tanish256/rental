<?php 
session_start();
if (isset($_SESSION['role'])) {
    // Redirect to login page if not logged in
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body{
            margin:0;
        }
        ::placeholder {
  color: #3D3C42;
}

::-ms-input-placeholder { /* Edge 12-18 */
  color: #3D3C42;
}
        .root{
            display:flex;
            background:#FAFBFF;
            align-items:center;
            height:100vh;
            background-image: url(assets/bg\ login.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            justify-content:center;
            font-family:sans-serif;
            .login-main{
                height:70%;
                width: 70%;
                overflow: hidden;
                display:flex;
                justify-content:center;
                border-radius:10px;
                background:white;
                box-shadow:#9197b334 1px 1px 5px;
                flex-direction:row-reverse;
                .brand{
                    width: 46%;
                    align-items:center;
                    justify-content:center;
                    display:flex;
                    color:white;
                    background:#9197B3;
                    flex-direction:column;
                    padding:2%;
                    .logo {
                        width: 60%;
                        display:flex;
                        gap:20px;
                        img{
                            width: 100%;
                    }}
                }
                form{
                    display:flex;
                    padding:2%;
                    align-items:center;
                    text-align:center;
                    align-items:center;
                    justify-content:center;
                    width: 46%;
                    flex-direction:column;
                    h3,p{
                        margin:10px 0;
                    }
                    input{
                        width: 50%;
                        margin:10px 0;
                        font-size:14px;
                        background:#9197b334;
                        border-radius:5px;
                        border:none;
                        padding:10px;
                    }
                    button{
                        width:50%;
                        padding:10px;
                        border-radius:5px;
                        border:none;
                        background:#0F5FC2;
                        color:white;

                    }
                }

            }
        }
        @media (max-width:600px) {
            .login-main{
                flex-direction:column !important;
                form{
                    width: 100% !important;
                }
                .brand{
                    width: 100% !important;
                }
            }
        }
    </style>
</head>
<body>
    <div class="root">
        <div class="login-main">
            <div class="brand">
                <div class="logo">
                    <img src="assets/rental.svg
                    " alt="">
            </div>
            v0.1
            </div>
            <form action="login.php" method="POST">
                <h3>login</h3> 
                <p>login to access rental</p>
                <?php
// Start the session
require 'config.php';

try {
    // Create a PDO connection
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the username and password from the form
        $inputUsername = $_POST['username'];
        $inputPassword = $_POST['password'];

        // Prepare the SQL statement to get the user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :username and status = 'active'");
        $stmt->execute(['username' => $inputUsername]);

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and verify the password
        if ($user && password_verify($inputPassword, $user['pass'])) {
            // Set session variables based on the username and role
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // Redirect to the appropriate page based on the role
            if ($_SESSION['role'] == 'admin') {
                //echo "logged in";
                header("Location: dashboard.php");
            } else {
                //echo "failed";
                header("Location: dashboard.php");
            }
            exit;
        } else {
            // Invalid login
            echo "<p style='color:red; margin:0; font-size:14px;'>Invalid username or password!</p>";
        }
    }
} catch (PDOException $e) {
    // Handle any errors with the database connection
    echo "Error: " . $e->getMessage();
}
?>

                <input type="text" name="username" placeholder="username">
                <input type="password" name="password" placeholder="password">
                <button>login</button>
            </form>

        </div>
    </div>
</body>
</html>