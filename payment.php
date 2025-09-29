<?php 
 require_once "Vhelper.php";
 if ($_SESSION['role'] == 'admin') {
  
 } else {
   echo '<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>403 Forbidden</title>
     <style>
         body {
             font-family: Arial, sans-serif;
             background-color: #f4f4f4;
             color: #333;
             text-align: center;
             padding: 50px;
         }
         h1 {
             font-size: 72px;
             color: #d9534f;
         }
         p {
             font-size: 18px;
         }
         button {
             background-color: #007bff;
             color: white;
             font-size: 16px;
             padding: 10px 20px;
             border: none;
             border-radius: 5px;
             cursor: pointer;
             text-decoration: none;
         }
         button:hover {
             background-color: #0056b3;
         }
     </style>
 </head>
 <body>
     <h1>403</h1>
     <p>Forbidden: You dont have permission to access this page.</p>
     <a href="javascript:history.back()">
         <button>Go Back</button>
     </a>
 </body>
 </html>
 ';
   exit;
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    tbody{
        max-height: 40vh;
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
            <!-- ----------------------------------table-------------------------------------------- -->
            <div class="tablecard">
                <div class="tops">
                    <div class="headers">
                        <h1>Landlords</h1>
                        <p>active Landlords</p>
                    </div>
                    <div class="right">
                        <input type="text" id="search" placeholder="Search..." onkeyup="filterTable()">
                        <div class="sort-component">
                            <label for="sort-options" class="sort-label">Sort_by:</label>
                            <select id="sort-options" class="sort-select" onchange="sortTable()">
                              <option value="name-asc">Name</option>
                              <option value="name-desc">Landlord</option>
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
                        <th>Total Rent</th>
                        <th>Outstanding</th>
                        <th>Total Paid</th>
                        <th>Amount</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($landlords as $landlord) {
                        $balances = getBalanceLandlord($landlord['id'],date("M"),date("Y"));
                        $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
                        $balance_bf = isset($balances[0]['balance_bf']) ? $balances[0]['balance_bf'] : 0;
                        $balance_due = isset($balances[0]['balance_due']) ? $balances[0]['balance_due'] : 0;

                        echo "<tr>";
                        echo "<td>{$landlord['name']}</td>";
                        echo "<td style='text-align: center;'>" . number_format($balance_bf + $balance_due) . "</td>";
                        echo "<td style='text-align: center;'>" . number_format($balance) . "</td>";
                        echo "<td style='text-align: center;'>" . number_format(($balance_due + $balance_bf) - $balance) . "</td>";
                        echo "<td><input type='number' class='payment' data-landlord-id='{$landlord['id']}'></td>";
                        echo "<td><button class='collect-btn' data-landlord-id='{$landlord['id']}'>pay</button></td>";
                        echo "</tr>";
                    }
                    ?>
                   
                </tbody>
            </table>
            </div>
            <!-- ----------------------------------table-------------------------------------------- -->

                        <!-- ----------------------------------table-------------------------------------------- -->
                        <div class="tablecard">
                            <div class="tops">
                                <div class="headers">
                                    <h1>Tenants</h1>
                                    <p>active Tenants</p>
                                </div>
                                <div class="right">
                                    <input type="text" id="search2" placeholder="Search..." onkeyup="filterTable2()">
                                    <div class="sort-component">
                                        <label for="sort-options" class="sort-label">Sort_by:</label>
                                        <select id="sort-options" class="sort-select" onchange="sortTable2()">
                                          <option value="name-asc">Name</option>
                                          <option value="name-desc">Landlord</option>
                                          <option value="date-asc">Status</option>
                                          <option value="date-desc">Balance</option>
                                        </select>
                                      </div>
                                </div>
                            </div>
                            
                        <table id="tenantTable2">
                            <thead>
                                <tr>
                                    <th>Tenant Name</th>
                                    <th>Total Rent</th>
                                    <th>Outstanding</th>
                                    <th>Total Paid</th>
                                    <th class="date">Date</th>
                                    <th>amount</th>
                                    <th>action</th>
                                </tr>
                            </thead>
                            <tbody class="collect">
                            <?php
                                // Loop through tenants and output the table rows
                            foreach ($tenants as $tenant) {
                                // Get room data from $rooms array
                                $room = getRoom($tenant['room_id'], $rooms);
                                $location = $room['location'];
                                $landlord = getLandlord($room['landlord'], $landlords);
                                $balances = isset($tenantBalances[$tenant['id']]) ? [$tenantBalances[$tenant['id']]] : [[]];
                                $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
                                $balance_bf = isset($balances[0]['balance_bf']) ? $balances[0]['balance_bf'] : 0;
                                $balance_due = isset($balances[0]['balance_due']) ? $balances[0]['balance_due'] : 0;
                                echo "<tr>";
                                echo "<td style='padding:5px'><span style='font-family:sans-serif; font-size:15px;'>{$tenant['name']}</span> <span style='color:red;font-size:10px;margin:0;padding:0;'>({$landlord['name']})</span></td>";
                                echo "<td style='text-align: center;'>" . number_format($balance_due + $balance_bf) . "</td>";
                                echo "<td style='text-align: center;'>" . number_format($balance) . "</td>";
                                echo "<td style='text-align: center;'>" . number_format(($balance_due + $balance_bf) - $balance) . "</td>";
                                echo "<td class='date'><input class='date' type='date' data-tenant-id='{$tenant['id']}'></td>";
                                echo "<td><input type='number' class='payment' data-tenant-id='{$tenant['id']}'></td>";
                                echo "<td><button class='collect-btn' data-tenant-id='{$tenant['id']}'>collect</button></td>";
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
    <script src="js/script.js"></script>
    <script>
        $(document).ready(function() {
    // Event listener for the 'collect' button click
    $('.collect-btn').on('click', function() {
    var tenantId = $(this).data('tenant-id');  // Get tenant ID from data attribute
    var landlordId = $(this).data('landlord-id');  // Get landlord ID from data attribute
    var paymentAmount = $(this).closest('tr').find('input.payment').val();  // Get the value from the input field
    var paydate = $(this).closest('tr').find('input.date').val();
    // Ensure the input is not empty
    if (paymentAmount && !isNaN(paymentAmount)) { 
        // Check if tenantId or landlordId is present
        if (tenantId  && paydate) {
            // Execute logic for tenant
            console.log("Tenant payment logic executed.");
            if (confirm(`Confirm payment of ${paymentAmount}`)) {
                $.ajax({
                url: 'postpayment.php', // The server-side script to handle the request
                type: 'POST',
                data: {
                    tenant_id: tenantId,
                    date: paydate,
                    payment: paymentAmount
                },
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            });
            }

        }else if(tenantId){
            alert('No date specified');
        } else if (landlordId) {
            // Execute logic for landlord
            console.log("Landlord payment logic executed.");
            //alert(paymentAmount);
            if (confirm(`Confirm payment of ${paymentAmount}`)) {
                $.ajax({
                url: 'postpayment.php', // The server-side script to handle the request
                type: 'POST',
                data: {
                    landlord_id: landlordId,
                    payment: paymentAmount
                },
                success: function(response) {
                    console.log(response); // You can log the response from the server here
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            });
            }
        } else {
            // No tenant or landlord ID found
            alert("No valid tenant or landlord ID found.");
        }
    } else {
        alert("Please enter a valid amount");
    }
});

});

    </script>
</body>
</html>