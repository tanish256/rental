<?php 
require 'Vhelper.php';
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
                        <h3>Total Landlords</h3>
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
                        <h1>Rooms</h1>
                        <p>all Rooms</p>
                    </div>
                    <div class="right">
                        <input type="text" id="search" placeholder="Search..." onkeyup="filterTable()">
                        <div class="sort-component">
                            <label for="sort-options" class="sort-label">Sort_by:</label>
                            <select id="sort-options" class="sort-select" onchange="sortTable()">
                                <option value="name-asc">Id</option>
                                <option value="landlord-asc">Landlord</option>
                                <option value="status-asc">Status</option>
                                <option value="balance-asc">Amount</option>
                            </select>
                        </div>
                    </div>
                </div>
            <table id="tenantTable">
                <thead>
                    <tr>
                        <th>Room Id</th>
                        <th>Landlord</th>
                        <th>Condition</th>
                        <th>Location</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
// Loop through tenants and output the table rows
foreach ($rooms as $room) {
    // Get room data from $rooms array
    $landlord = getLandlord($room['landlord'], $landlords);
    echo "<tr>";
    echo "<td>#{$room['id']}</td>";
    echo "<td>{$landlord['name']}</td>";
    echo "<td>{$room['roomcondition']}</td>";
    echo "<td>{$room['location']}</td>";
    echo "<td>ugx " . number_format($room['amount'], 0, '.', ',') . "</td>";
    if (roomHasTenant($pdo, $room['id'])) {
        echo "<td class='status-active'><div>occupied</div></td>";
    } else {
        echo "<td class='status-inactive'><div>vacant</div></td>";
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
    <script>
    </script>
    <script src="js/filter.js"></script>
</body>
</html>