<?php
require 'auth-check.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rental</title>
    <link rel="stylesheet" href="css/style.css">
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
        <?php include 'sidebar.php'; ?>
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

            <!-- ................................summary.................................... -->

            <div class="summary">

                <div class="sum">
                    <div class="circle">
                        <img src="assets/profile-2user.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total Tenants</h3>
                        <h4><?php echo $ttenants?></h4>
                        <p>this month</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <img src="assets/profile-tick.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total landlords</h3>
                        <h4><?php echo $tlandlords?></h4>
                        <p>this month</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <img src="assets/monitor.svg" alt="">
                    </div>
                    <div class="inf">
                    <h3>Vacant Rooms</h3>
                        <h4><?php echo count($roomsWithoutTenant)?></h4>
                        <p>this month</p>
                    </div>
                </div>

            </div>
            <!-- ................................summary.................................... -->

            <!-- ..............................................metrics1....................... -->
            <div class="metrics" <?php if ($_SESSION['role'] == 'admin'){}else {echo "Style='display:none;'";}?>>
                <div class="card">
                    <p>UGX <?php echo number_format($total_balance_bfw, 0, '.', ',')?></p>
                    <h3><span style="color:red">Balance b/F</span></h3>
                    <p>this month</p>
                </div>

                <div class="card">
                    <p>UGX <?php echo number_format($total_balance_duew, 0, '.', ',') ?></p>
                    <h3>Expected Gross</h3>
                    <p>this month</p>
                </div>

                <div class="card">
                    <p>UGX <?php echo number_format($total_balance_duew+$total_balance_bfw-$total_balance, 0, '.', ',') ?></p>
                    <h3><span style="color:green">Total Payment</span></h3>
                    <p>this month</p>
                </div>

                <div class="card">
                    <p>UGX <?php echo number_format($total_balance, 0, '.', ',')  ?></p>
                    <h3>Total Balance</h3>
                    <p>this month</p>
                </div>
            </div>
            <!-- ..................................metrics1........................................... -->
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
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/graphs.js"></script>
    
</body>

</html>