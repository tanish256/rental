<?php
require '../helpers/auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rental</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- Loader HTML -->
<div id="loader">
  <div class="spinner"></div>
</div>

<script>
  // Hide loader after page is fully loaded
  window.addEventListener("load", function() {
    document.getElementById("loader").style.display = "none";
  });
</script>

    <div class="root">
        <?php include '../components/sidebar.php'; ?>
        <div class="dashmain">
            <style>
                h2.name{
                    width: 100%;
                    font-size:20px;
                    color:#333;
                    font-weight:bold;
                    margin:0;
                }
            </style>
        <h2 class='name'>Welcome back <?php echo $_SESSION['name'];?></h2>

            <!-- ..summary.. -->
             <?php include '../components/summary.php'; ?>

            <!-- ..metrics.. -->
             <?php include '../components/metrics.php'; ?>

            <style>
                .graphs{
                    width: 92%;
                    justify-content: space-around;
                    display: flex;
                    background-color: #94d0ea;
                    padding: 10px;
                }
                .graphs.properties{
                    background-color: #ea9494;
                }
                .graphs .chart1{
                    width: 47%;
                    background-color: white;
                    border-radius: 2px;
                    padding: 5px;
                }
                .graphs .chart2{
                    width: 47%;
                    background-color: white;
                    border-radius: 2px;
                    padding: 5px;
                }

            </style>
            <div class="graphs income">
                <div class="chart1">
                <div id="chart"></div>
                </div>
                <div class="chart2">
                    <div id="chart2"></div>
                    </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/graphs.js"></script>
    
</body>

</html>