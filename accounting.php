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
<style>
  tr {
      cursor: pointer;
    }
    .metrics{
      width: 100%;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      .card{
        width: 23%;
        padding: 10px;
      }
    }
    .root .dashmain{
      width: 72%;
    }
    .date{
      gap: 10px;
      width: 100%;
      align-items: center;
      padding-inline: 20px;
      opacity:0.4;
      user-select: none;
      pointer-events: none;
      display: flex;
      font-weight: bold;
      p{
        color: rgb(32, 85, 32);
        margin: 0;
      }
      .sort-component{
      width: fit-content;
      .sort-select{
        width: 100px;
        font-weight: bold;
      }
    }
    }
    
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

            <!-- ..............................................metrics1....................... -->
            <div class="metrics">
              
              <div class="date">
                <p>Financial Year</p>
                <div class="sort-component">
                  <select id="sort-options" class="sort-select">
                    <option value="name-asc"><?php echo date('Y')?></option>
                    <option value="name-desc">2022</option>
                    <option value="date-asc">2023</option>
                    <option value="date-desc">2024</option>
                  </select>
                </div>
                <div class="sort-component">
                  <select id="sort-options" class="sort-select">
                    <option value="name-asc"><?php echo date('M')?></option>
                    <option value="name-desc">Feb</option>
                    <option value="date-asc">Mar</option>
                    <option value="date-desc">Aprirufgro</option>
                  </select>
                </div>
                <div class="month"></div>
              </div>
              <div class="card">
                    <p>UGX <?php echo number_format($total_balance_bfw, 0, '.', ',')?></p>
                    <h3>Balance b/F</h3>
                    <p>this month</p>
                </div>

                <div class="card">
                    <p>UGX <?php echo number_format($total_balance_duew, 0, '.', ',') ?></p>
                    <h3>Expected Gross</h3>
                    <p>this month</p>
                </div>

                <div class="card">
                    <p>UGX <?php echo number_format($total_balance_duew+$total_balance_bfw-$total_balance, 0, '.', ',') ?></p>
                    <h3>Total Payment</h3>
                    <p>this month</p>
                </div>

                <div class="card">
                    <p>UGX <?php echo number_format($total_balance, 0, '.', ',')  ?></p>
                    <h3>Total Balance</h3>
                    <p>this month</p>
                </div>
                <?php
                  // First calculate all profits
                  $exp_profit = 0;
                  $got_profit = 0;

                  foreach ($landlords as $landlord) {
                      $tenantsl = getTenantsByLandlord($landlord['id']);
                      $tenantst = json_decode($tenantsl, true);
                      
                      $total_balance_due = 0;
                      $total_balance_bf = 0;
                      $total_balance = 0;
                      foreach ($tenantst as $tenant) {
                          $total_balance_due += isset($tenant['balance_due']) ? $tenant['balance_due'] : 0;
                          $total_balance += isset($tenant['balance']) ? $tenant['balance'] : 0;
                          $total_balance_bf += isset($tenant['balance_bf']) ? $tenant['balance_bf'] : 0;
                      }
                      $final_balance = $total_balance_due + $total_balance_bf - $total_balance;
                      $commission =getcomission($landlord,$final_balance,count($tenantst));
                      $commission_main = getcomission($landlord, $total_balance_due, count($tenantst));
                      $exp_profit += $commission_main;
                      $got_profit += $commission;
                  }
                ?>

                <div class="card">
                  <p>UGX <?php echo number_format($exp_profit, 0, '.', ',') ?></p>
                  <h3>Expected Profit</h3>
                  <p> this month</p>
              </div>
  
              <div class="card">
                  <p>UGX <?php echo number_format($got_profit, 0, '.', ',')?> </p>
                  <h3>Accumulated Profit</h3>
                  <p> this month</p>
              </div>
            </div>
            <!-- ..................................metrics1........................................... -->



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
     "<span style='color:#007bff;font-size:11px;margin:0;padding:0;'>(" . number_format($commission_main) . ")</span></td>";
                        echo "<td>". number_format($final_balance) ."<span style='color:#007bff;font-size:11px;margin:0;padding:0;'>(" . number_format($commission) . ")</span></td>";
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

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/accounting.js"></script>
</body>
</html>