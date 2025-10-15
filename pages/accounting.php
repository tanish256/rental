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
          <?php include '../components/metrics.php'; ?>
            <!-- ----------------------------------table-------------------------------------------- -->
            <div class="tablecard">
                <div class="tops">
                  <div class="headers">
                    <h1>Landlord</h1>
                    <p>Active Landlord</p>
                  </div>
                  <div class="right">
                    <input type="text" id="search" placeholder="Search..." onkeyup="filterTable()">
                    <div class="sort-component">
                      <label for="sort-options" class="sort-label">Sort_by:</label>
                      <select id="sort-options" class="sort-select" onchange="sortTable()">
                        <option value="name-asc">Name</option>
                        <option value="date-asc">Status</option>
                        <option value="date-desc">Balance</option>
                      </select>
                    </div>
                  </div>
                </div>
                <table id="tenantTable">
                  <thead>
                    <tr>
                      <th>Landlord Name</th>
                        <th>Total B/F</th>
                        <th>Rent Due</th>
                        <th>Total Paid</th>
                        <th>Total Balance</th>
                        <th>action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      foreach ($landlords as $landlord) {
                        echo "<tr class='clickable-row' data-id='{$landlord['id']}'>";
                    
                        // Initialize sums for the landlord
                        $total_balance = 0;
                        $total_balance_due = 0;
                        $total_balance_bf = 0;
                    
                        // Get tenants for this landlord
                        $tenantsl = getTenantsByLandlord($landlord['id']);
                        // If the function returns a JSON-encoded string, you need to decode it
                        $tenantst = json_decode($tenantsl, true);
                        $status_cleared = true;
                    
                        // Loop through each tenant and sum their balances
                        foreach ($tenantst as $tenant) {
                            $total_balance += isset($tenant['balance']) ? $tenant['balance'] : 0;
                            $total_balance_due += isset($tenant['balance_due']) ? $tenant['balance_due'] : 0;
                            $total_balance_bf += isset($tenant['balance_bf']) ? $tenant['balance_bf'] : 0;
                            if (isset($tenant['balance']) && $tenant['balance'] >0) {
                              $status_cleared = false;  // Mark as cleared if any tenant has balance <= 0
                          }
                        }
                    
                        // Calculate final balance
                        $final_balance = $total_balance_due + $total_balance_bf - $total_balance;

                        $commission_main=getcomission($landlord,$total_balance_due,count($tenantst));
                        $commission =getcomission($landlord,$final_balance,count($tenantst));
                        $exp_profit += $commission_main;
                        $got_profit += $commission;

                        // Display the landlord's name and balance data
                        echo "<td>{$landlord['name']}</td>";
                        echo "<td>" . number_format($total_balance_bf) . 
     "</td>";
                        echo "<td>" . number_format($total_balance_due) . 
     "</td>";
                        echo "<td>". number_format($final_balance) ."</td>";
                        echo "<td>ugx " . number_format($total_balance, 0, '.', ',') . "</td>";
                    
                        // Determine the status based on the total balance
                        if ($status_cleared) {
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

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/filter.js"></script>
    <script src="../js/accounting.js"></script>
</body>
</html>