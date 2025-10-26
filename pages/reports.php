<?php
require '../helpers/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Coming Soon | Rental</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #f3f6f9;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            overflow: hidden;
        }

        .comingsoon-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: #333;
        }

        .comingsoon-container h1 {
            font-size: 48px;
            font-weight: bold;
            color: #1a73e8;
            margin-bottom: 10px;
        }

        .comingsoon-container p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }

        .countdown div {
            background: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .countdown span {
            display: block;
            font-size: 30px;
            font-weight: bold;
            color: #333;
        }

        .countdown label {
            font-size: 13px;
            color: #666;
        }

        footer {
            position: absolute;
            bottom: 20px;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- Loader -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <script>
        window.addEventListener("load", function() {
            document.getElementById("loader").style.display = "none";
        });
    </script>

    <div class="root">
        <?php include '../components/sidebar.php'; ?>
        <div class="dashmain">
            <div class="comingsoon-container">
                <h1>🚧 Coming Soon</h1>
                <p>We’re working hard to bring you something amazing.<br>
                Please check back soon!</p>
            </div>

            <footer>
                &copy; <?php echo date("Y"); ?> Rental Dashboard — All Rights Reserved.
            </footer>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
