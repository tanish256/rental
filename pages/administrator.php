<?php 
 require "../helpers/auth-check.php";
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


            <div class="tabs-container">
                  <div class="tabs">
                    <button class="tab-button active" data-tab="landlords">Landlords</button>
                    <button class="tab-button" data-tab="rooms">Rooms</button>
                    <button class="tab-button" data-tab="tenants">Tenants</button>
                </div>

                  <!-- LANDLORDS -->
                    <div id="landlords" class="tab-content active">
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
                            <div class="tadd">
                                <button onclick="Rlandlord()">Add</button>
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
                                // Loop through tenants and output the table rows
                                foreach ($landlords as $landlord) {
                                    $balances = getBalanceLandlord($landlord['id'],date("M"),date("Y"));
                                    $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;
                                    echo "<tr>";
                                    echo "<td>{$landlord['name']}</td>";
                                    echo "<td class='email'>{$landlord['email']}</td>";
                                    echo "<td>{$landlord['contact']}</td>";
                                    echo "<td>{$landlord['location']}</td>";
                                    echo "<td>ugx {$balance}</td>";
                                    echo "<td class='status-edit'><div onclick='Elandlord({$landlord['id']})'>edit</div></td>";
                                    echo "</tr>";
                                }
                                ?>   
                                </tbody>
                            </table>
                        </div>
                        <!-- ----------------------------------table-------------------------------------------- -->
                    </div>

                      <!-- TENANTS -->
                    <div id="tenants" class="tab-content">
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
                                        <select id="sort-options" class="sort-select">
                                            <option value="name-asc">Name</option>
                                            <option value="name-desc">Landlord</option>
                                            <option value="date-asc">Status</option>
                                            <option value="date-desc">Balance</option>
                                        </select>
                                        </div>
                                </div>
                            </div>
                            <div class="tadd">
                                <button onclick="RTenant()">Add</button>
                            </div>
                            
                            <table id="tenantTable2">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Landlord</th>
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
                                        //$location = $room['location'];
                                    // $landlord = getLandlord($room['landlord'], $landlords);
                                        $balances = isset($tenantBalances[$tenant['id']]) ? [$tenantBalances[$tenant['id']]] : [[]];
                                        $balance = isset($balances[0]['total_balance']) ? $balances[0]['total_balance'] : 0;

                                        // Replace placeholder names and balance with data from JSON
                                        echo "<tr>";
                                        echo "<td>{$tenant['name']}</td>";
                                        echo $landlordt;
                                        echo "<td>{$tenant['contact']}</td>";
                                        echo $location;
                                        echo "<td>ugx {$balance}</td>";
                                        echo "<td class='status-edit' onclick='TEdit({$tenant['id']})'><div>edit</div></td>";
                                        echo "</tr>";
                                    }
                                ?>
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                        <!-- ----------------------------------table-------------------------------------------- -->
                    </div>

                    <div id="rooms" class="tab-content">
                        <!-- ----------------------------------table-------------------------------------------- -->
                        <div class="tablecard">
                            <div class="tops">
                                <div class="headers">
                                    <h1>Rooms</h1>
                                    <p>all Rooms</p>
                                </div>
                                <div class="right">
                                    <input type="text" id="search3" placeholder="Search..." onkeyup="filterTable3()">
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
                            <table id="tenantTable3">
                                <thead>
                                    <tr>
                                        <th>Room Id</th>
                                        <th>Landlord</th>
                                        <th>Status</th>
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
                                        if (roomHasTenant($pdo, $room['id'])) {
                                            echo "<td class='status-active'><div>occupied</div></td>";
                                        } else {
                                            echo "<td class='status-inactive'><div>vacant</div></td>";
                                        }
                                        // echo "<td>{$room['roomcondition']}</td>";
                                        echo "<td>{$room['location']}</td>";
                                        echo "<td>ugx " . number_format($room['amount'], 0, '.', ',') . "</td>";
                                        echo "<td class='status-edit' onclick='Redit({$room['id']})'><div>edit</div></td>";
                                        echo "</tr>";
                                    }
                                ?> 
                                </tbody>
                            </table>
                        </div>
                        <!-- ----------------------------------table-------------------------------------------- -->
                    </div>
            </div>

            

        </div>
    </div>
    <style>
        .Tparent{
            h2{
                font-family: sans-serif;
                font-size: 1.5em;
                margin-inline-start: 20px;
                font-weight: normal;
            }
            form{
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                padding-block: 10px;
                gap: 5%;
                row-gap: 10px;
                input{
                    width: 40%;
                    height: fit-content;
                    border-radius: 5px;
                    border: none;
                    padding:15px 10px;
                    background-color: #9197b334;
                }
            }
        }
    </style>
    <div class="Tparent landlord r">
        <div class="card">
            <div class="x" id="xl">x</div>
            <h2>Register A Landlord</h2>
            <form action="" id="landlordForm">
            <input type="text" name="name" placeholder="Landlord" required>
            <input type="text" name="contact" placeholder="Contact" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="location" placeholder="Location" required>
            <button class="savebtn" type="submit">Submit</button>
            </form>
        </div>
        
    </div>
    <div class="Tparent landlord e">
        <div class="card">
            <div class="x" id="xle">x</div>
            <h2>Edit A Landlord</h2>
            <form action="" id="landlordForm2">
            <input type="text" id="lidd" placeholder="#id" disabled>
            <input type="text" name="lname" id="lname" placeholder="Landlord" required>
            <input type="text" name="lcontact" id="lcontact" placeholder="Contact">
            <input type="email" name="lemail" id="lemail" placeholder="Email">
            <input type="text" name="llocation" id="llocation" placeholder="Location" required>
            <input type="number" name="lrooms" id="lrooms" min="0" placeholder="add number of rooms">
            <input type="hidden" name="lid" id="lid">
            <input type="text" name="" id="ldate" placeholder="date registered" disabled>
            <button class="savebtn" type="submit">Submit</button>
            <div id="Ldel" class='delete' onclick='Ldel(2)'>delete</div>
            </form>
        </div>
        
    </div>
    <div class="Tparent tenant edit">
        <div class="card">
            <div class="x" id="xt">x</div>
            <h2>Edit A Tenant</h2>
            <form action="" id="EditTenant">
                <input type="text" name="" id="tid" placeholder="Id" disabled>
                <input type="hidden" name="tid" id="tidd" placeholder="Id">
                <input type="text" name="tname" id="tname" placeholder="Tenant Name">
                <input type="text" name="tcontact" id="tcontact" placeholder="Contact">
                <input type="text" name="" id="tlocation" placeholder="location" disabled>
                <input type="text" name="" id="tbalance" placeholder="Balance" disabled>
                <input type="text" name="troom"  placeholder="Room Id">
                <input type="text" name="" id="tlandlord" placeholder="Landlord" disabled>
                <input type="text" name="" id="tdate" placeholder="date registered" disabled>
                <button type="submit" class="savebtn">Save</button>
                <div id="Tdel" class='delete' onclick='Tdel(2)'>delete</div>
            </form>
        </div>
        
    </div>
    <div class="Tparent tenant r">
        <div class="card">
            <div class="x" id="xtr">x</div>
            <h2>Register A Tenant</h2>
            <form action="" id="RegisterT">
                <input type="text" name="trname" placeholder="Tenant Name">
                <input type="text" name="trcontact" placeholder="Contact">
                <input type="number" name="trroom" placeholder="Room">
                <button type="submit" class="savebtn">Save</button>
            </form>
        </div>
        
    </div>
    <div class="Tparent room">
        <div class="card">
            <div class="x" id="xr">x</div>
            <h2>Edit A Room</h2>
            <form action="" id="Editrrom">
                <input type="text" name="" id="rid" placeholder="Room id#" disabled>
                <input type="text" name="rid" id="ridd" hidden>
                <input type="text" name="" id="landlordname" placeholder="Landlord" disabled>
                <input type="text" name="rcondition" id="rcondition" placeholder="Condition">
                <input type="text" name="rlocation" id="rlocation" placeholder="Location">
                <input type="text" name="ramount" id="ramount" placeholder="Amount">
                <button type="submit" class="savebtn">Save</button>
                <div id="rdel" class='delete' onclick='Rdel(2)'>delete</div>
            </form>
        </div>
        
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/filter.js"></script>
    <script src="../js/script.js"></script>
    <script>
        function Tdel(tid) {
            if (confirm('Are you sure you want to delete Tenant #'+tid)) {
                var formData = {
                    id: tid,
                    del: 1
                };
                $.ajax({
                    url: "../api/RegisterTenant.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(formData),
                    success: function(response){
                        console.log(response.message);   
                        location.reload();
                    },
                    error: function(xhr, status, error){
                        alert("Error: " + xhr.responseText);
                    }
                });
            } else {
            }
        }
    </script>
    <script>
        function Ldel(tid) {
            if (confirm('Are you sure you want to delete Landlord #'+tid)) {
                var formData = {
                    id: tid,
                    del: 1
                };
                $.ajax({
                    url: "../api/RegisterLandlord.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(formData),
                    success: function(response){
                        console.log(response.message);   
                        //alert(response.message);
                        location.reload();
                    },
                    error: function(xhr, status, error){
                        alert("Error: " + xhr.responseText);
                    }
                });
            } else {
            }
        }
    </script>
    <script>
        document.querySelectorAll(".tab-button").forEach(button => {
            button.addEventListener("click", () => {
            // remove active class from all buttons and content
            document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
            document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));
            
            // activate the clicked tab and its content
            button.classList.add("active");
            const tabId = button.getAttribute("data-tab");
            document.getElementById(tabId).classList.add("active");
            });
        });
    </script>

</body>
</html>