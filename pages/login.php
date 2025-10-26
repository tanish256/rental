<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['role']) && $_SESSION['loggedin'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Initialize variables
$error_message = '';
$username = '';

// Process login if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../helpers/config.php';
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare the SQL statement to get the user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :username AND status = 'active'");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check if the user exists and verify the password
        if ($user && password_verify($password, $user['pass'])) {
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login_time'] = time();
            
            // Redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = "Invalid username or password!";
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $error_message = "System error. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rental System</title>
    <style>
        :root {
            --primary-color: #0F5FC2;
            --primary-light: #9197B3;
            --primary-lighter: #9197b334;
            --text-color: #3D3C42;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --white: #ffffff;
            --background: #FAFBFF;
            --shadow: rgba(145, 151, 179, 0.2);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--background);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        .root {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            background-image: url(assets/bg\ login.png);
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        
        .login-container {
            width: 100%;
            max-width: 900px;
            display: flex;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px var(--shadow);
            background: var(--white);
        }
        
        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: var(--white);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 30px;
        }
        
        .logo {
            width: 180px;
            height: auto;
        }
        
        .brand-text {
            margin-top: 20px;
        }
        
        .brand-text h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .brand-text p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .form-section {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h2 {
            font-size: 28px;
            color: var(--primary-color);
            margin-bottom: 8px;
        }
        
        .form-header p {
            color: var(--text-color);
            opacity: 0.7;
        }
        
        .login-form {
            width: 100%;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            font-size: 16px;
            background: var(--primary-lighter);
            border: 2px solid transparent;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(15, 95, 194, 0.1);
        }
        
        .form-input::placeholder {
            color: var(--text-color);
            opacity: 0.6;
        }
        
        .error-message {
            color: var(--error-color);
            font-size: 14px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            background: #0d4ea6;
        }
        
        .submit-btn:active {
            transform: translateY(1px);
        }
        
        .version {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            opacity: 0.7;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }
            
            .brand-section {
                padding: 30px 20px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 480px) {
            .root {
                padding: 10px;
            }
            
            .brand-section, .form-section {
                padding: 25px 15px;
            }
            
            .logo {
                width: 140px;
            }
            
            .brand-text h1 {
                font-size: 24px;
            }
            
            .form-header h2 {
                font-size: 24px;
            }
        }
        
        /* Loading state */
        .loading {
            position: relative;
            pointer-events: none;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Success message */
        .success-message {
            color: var(--success-color);
            font-size: 14px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="root">
        <div class="login-container">
            <div class="brand-section">
                <div class="logo-container">
                    <img src="../assets/rental.svg" alt="Rental System Logo" class="logo">
                </div>
                <div class="brand-text">
                    <p>Efficiently manage your rental properties</p>
                </div>
            </div>
            
            <div class="form-section">
                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p>Sign in to access your account</p>
                </div>
                
                <form class="login-form" action="login.php" method="POST" id="loginForm">
                    <?php if (!empty($error_message)): ?>
                        <div class="error-message">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="username" 
                            placeholder="Username" 
                            class="form-input" 
                            value="<?php echo htmlspecialchars($username); ?>"
                            required
                            autocomplete="username"
                        >
                    </div>
                    
                    <div class="form-group">
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Password" 
                            class="form-input" 
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    
                    <button type="submit" class="submit-btn" id="submitBtn">Sign In</button>
                    
                    <div class="version">
                        v.07
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Signing In...';
        });
    </script>
</body>
</html>