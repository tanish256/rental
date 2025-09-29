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
                        <h1>Tenants</h1>
                        <p>active Tenants</p>
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
                            <th>landlord</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            // Loop through tenants and output the table rows
                            foreach ($tenants as $tenant) {
                                // Get room data from $rooms array
                                $room = getRoom($tenant['room_id'], $rooms);
                                if ($room) {
                                    $location = "<td>{$room['location']}</td>";
                                    $landlord = getLandlord($room['landlord'], $landlords);
                                    $tenant['landlord']= $landlord['name'];
                                    $landlordt="<td>{$tenant['landlord']}</td>";
                                }else{
                                    $location = "<td style='color:red'>not in any room</td>";
                                    $tenant['landlord']="not in any room";
                                    $landlordt="<td style='color:red'>{$tenant['landlord']}</td>";
                                }
                                $balances = isset($tenantBalances[$tenant['id']]) ? [$tenantBalances[$tenant['id']]] : [[]];
                                $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
                                $tenant['balance'] = $balance;
                                echo "<tr class='TReport' onclick='TReport({$tenant['id']})'>";
                                echo "<td>{$tenant['name']}</td>";
                                echo $landlordt;
                                echo "<td>{$tenant['contact']}</td>";
                                echo $location;
                                echo "<td>ugx " . number_format($tenant['balance'], 0, '.', ',') . "</td>";
                                if ($tenant['balance'] <= 0) {
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

    <div class="Tparent tenant edit">
    <div class="card">
        <div class="x" id="xt">×</div>
        <div class="header">
            <h2>Tenant Information</h2>
            <div class="subtitle">View and manage tenant details</div>
        </div>
        <div class="content">
            <form action="">
                <div class="form-group">
                    <label for="tid"><span class="icon">#</span> Tenant ID</label>
                    <input type="text" name="" id="tid" placeholder="Tenant ID" disabled>
                </div>
                <div class="form-group">
                    <label for="tname"><span class="icon">👤</span> Tenant Name</label>
                    <input type="text" name="" id="tname" placeholder="Tenant Name" disabled>
                </div>
                <div class="form-group">
                    <label for="tcontact"><span class="icon">📞</span> Contact</label>
                    <input type="text" name="" id="tcontact" placeholder="Contact" disabled>
                </div>
                <div class="form-group">
                    <label for="tlocation"><span class="icon">📍</span> Location</label>
                    <input type="text" name="" id="tlocation" placeholder="Location" disabled>
                </div>
                <div class="form-group">
                    <label for="tbalance">
                        <span class="icon">💰</span> Balance
                        <span id="status-badge" class="status-badge pending">Pending</span>
                    </label>
                    <input type="text" name="" id="tbalance" placeholder="Balance" disabled>
                </div>
                <div class="form-group">
                    <label for="troom"><span class="icon">🏠</span> Room ID</label>
                    <input type="text" name="" id="troom" placeholder="Room ID" disabled>
                </div>
                <div class="form-group">
                    <label for="tlandlord"><span class="icon">👨‍💼</span> Landlord</label>
                    <input type="text" name="" id="tlandlord" placeholder="Landlord" disabled>
                </div>
                <div class="form-group">
                    <label for="tamount"><span class="icon">💵</span> Monthly Rent</label>
                    <input type="text" name="" id="tamount" placeholder="Monthly Rent" disabled>
                </div>
                <div class="form-group">
                    <label for="tdate"><span class="icon">📅</span> Date Registered</label>
                    <input type="text" name="" id="tdate" placeholder="Date Registered" disabled>
                </div>
            </form>
        </div>
        <div class="footer">
            <a id="thistory" target="_blank" href="transhistry.php?tenant=2" class="btn btn-primary">
                <span class="icon">📊</span> Transaction History Report
            </a>
        </div>
    </div>
</div>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/filter.js"></script>
</body>

</html>