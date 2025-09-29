<?php 
 require "Vhelper.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
</style>
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

            <div class="summary">
                
                <!-- ...................................summary.................................. -->
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
            <!-- ...................................summary.................................. -->

            <!-- ----------------------------------table-------------------------------------------- -->
            <div class="tablecard">
                <div class="tops">
                    <div class="headers">
                        <h1>landlords</h1>
                        <p>active landlords</p>
                    </div>
                    <div class="right">
                        <input type="text" id="search" placeholder="Search..." onkeyup="filterTable()">
                        <div class="sort-component">
                            <label for="sort-options" class="sort-label">Sort_by:</label>
                            <select id="sort-options" class="sort-select" onchange="sortTable()">
                                <option value="name-asc">Name</option>
                                <option value="landlord-asc">Landlord</option>
                                <option value="status-asc">Status</option>
                                <option value="balance-asc">Balance</option>
                            </select>
                        </div>
                    </div>
                </div>
                
            <table id="tenantTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($landlords as $landlord) {
                        echo "<tr class='TReport' onclick='TReport({$landlord['id']})'>";
                        echo "<td>{$landlord['name']}</td>";
                        echo "<td class='email'>{$landlord['email']}</td>";
                        echo "<td>{$landlord['contact']}</td>";
                        echo "<td>{$landlord['location']}</td>";
                        $balances = getBalanceLandlord($landlord['id'],date("M"),date("Y"));
                        $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
                        $landlord['balance'] = $balance;
                        echo "<td>ugx " . number_format($landlord['balance'], 0, '.', ',') . "</td>";
                        if ($landlord['balance'] <= 0) {
                            echo "<td class='status-active'><div>cleared</div></td>";
                        } else {
                            echo "<td class='status-inactive'><div>pending</div></td>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            </div>
            <!-- ----------------------------------table-------------------------------------------- -->

        </div>
    </div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/filter.js"></script>
</body>
</html>