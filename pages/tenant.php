<?php 
 require "../helpers/Vhelper.php";
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
            <!-- ..summary.. -->
             <?php include '../components/summary.php'; ?>
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

    <!-- Tenant Information Modal -->
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
            <?php
            if($_SESSION['role'] != 'user') {
                echo '<style>.btn-red{background:red;color:white;}</style>';
                echo '<a id="tdisable" class="btn btn-red">
                <span class="icon">🗑️</span> disable
            </a>';
                }
             ?>
            <?php
            if($_SESSION['role'] != 'user') {
                echo '<a id="tpayment" class="btn btn-success">
                <span class="icon">💵</span> payment
            </a>';
                }
             ?>
            
            <a id="thistory" target="_blank" href="transhistry.php?tenant=2" class="btn btn-primary">
                <span class="icon">📊</span> Transaction History Report
            </a>
        </div>
    </div>
</div>

    <!-- Payment Modal -->
    <div class="Tparent" id="paymentModal">
        <div class="card">
            <button class="x" id="closePaymentModal">&times;</button>
            <div class="header">
                <h2>Payment for <span id="paymentTenantName"></span></h2>
                <div class="subtitle">Record new payment and view recent transactions</div>
            </div>
            <div class="content">
                <!-- Recent Transactions Section -->
                <div class="recent-transactions">
                    <h3 style="margin-bottom: 15px; color: #333; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                        Last 3 Transactions
                    </h3>
                    <div id="recentTransactions" style="max-height: 200px; overflow-y: auto; margin-bottom: 20px;">
                        <!-- Transactions will be loaded here -->
                    </div>
                </div>

                <!-- Payment Form -->
                <form id="paymentForm">
                    <input type="hidden" id="paymentTenantId" name="tenant_id">
                    
                    <div class="form-group">
                        <label for="paymentDate">
                            <span class="icon">📅</span> Payment Date *
                        </label>
                        <input type="date" id="paymentDate" name="date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="paymentAmount">
                            <span class="icon">💰</span> Payment Amount (UGX) *
                        </label>
                        <input type="number" id="paymentAmount" name="payment" placeholder="Enter amount" required min="1">
                    </div>

                    <!-- Summary Section -->
                    <div class="payment-summary" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
                        <h4 style="margin: 0 0 10px 0; color: #333;">Payment Summary</h4>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-weight: 500;">Total Balance:</span>
                            <span id="summaryTotalBalance" style="font-weight: 600;">UGX 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-weight: 500;">Paid This Month:</span>
                            <span id="summaryPaidThisMonth" style="font-weight: 600; color: #28a745;">UGX 0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-weight: 500;">New Payment:</span>
                            <span id="summaryNewPayment" style="font-weight: 600; color: #007bff;">UGX 0</span>
                        </div>
                        <hr style="margin: 10px 0;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-weight: 600;">Remaining Balance:</span>
                            <span id="summaryRemainingBalance" style="font-weight: 700; color: #dc3545;">UGX 0</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="footer">
                <button class="btn btn-outline" id="cancelPaymentBtn">Cancel</button>
                <button class="btn btn-success" id="submitPaymentBtn">
                    <span class="icon">💳</span> Process Payment
                </button>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/filter.js"></script>
    <script>
        // Global variables
        let currentTenantId = null;
        let currentBalance = 0;
        let paidThisMonth = 0;
        document.getElementById('tdisable').addEventListener('click', function() {
            const rawValue = document.getElementById('tid').value;
            const tenantId = rawValue.replace('tenant id: #', '');
            if (!tenantId) {
                alert('No tenant selected');
                return;
            }

            if (confirm('Are you sure you want to disable this tenant? This action cannot be undone.')) {
                $.ajax({
                    url: '../api/RegisterTenant.php',
                    type: 'POST',
                    data: { id: tenantId,del: true },
                    success: function(response) {
                        alert('Tenant disable successfully!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting tenant:', error);
                        alert('Error deleting tenant. Please try again.');
                    }
                });
            }
        });
        // Open payment modal
        document.getElementById('tpayment').addEventListener('click', function() {
            const rawValue = document.getElementById('tid').value;
            const tenantId = rawValue.replace('tenant id: #', '');
            const tenantName = document.getElementById('tname').value;
            
            if (!tenantId) {
                alert('No tenant selected');
                return;
            }

            currentTenantId = tenantId;
            openPaymentModal(tenantId, tenantName);
        });

        // Open payment modal function
        function openPaymentModal(tenantId, tenantName) {
            document.getElementById('paymentTenantName').textContent = tenantName;
            document.getElementById('paymentTenantId').value = tenantId;
            
            // Load recent transactions and balance info
            loadRecentTransactions(tenantId);
            loadBalanceInfo(tenantId);
            
            // Show modal
            document.getElementById('paymentModal').classList.add('active');
        }

        // Close payment modal
        document.getElementById('closePaymentModal').addEventListener('click', closePaymentModal);
        document.getElementById('cancelPaymentBtn').addEventListener('click', closePaymentModal);

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.remove('active');
            document.getElementById('paymentForm').reset();
            currentTenantId = null;
            currentBalance = 0;
            paidThisMonth = 0;
            updateSummary();
        }

        // Load recent transactions
        function loadRecentTransactions(tenantId) {
            $.ajax({
                url: '../api/get_recent_transactions.php',
                type: 'GET',
                data: { tenant_id: tenantId },
                success: function(response) {
                    const transactions = response;
                    displayRecentTransactions(transactions);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading transactions:', error);
                    document.getElementById('recentTransactions').innerHTML = 
                        '<div style="text-align: center; color: #666; padding: 20px;">Error loading transactions</div>';
                }
            });
        }

        // Display recent transactions
        function displayRecentTransactions(transactions) {
            const container = document.getElementById('recentTransactions');
            
            if (transactions.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: #666; padding: 20px;">No recent transactions found</div>';
                return;
            }

            let html = '';
            transactions.forEach(transaction => {
                const date = new Date(transaction.date_paid).toLocaleDateString();
                const amount = new Intl.NumberFormat().format(transaction.amount);
                
                html += `
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #f0f0f0;">
                        <div>
                            <div style="font-weight: 500;">${date}</div>
                            <div style="font-size: 0.8rem; color: #666;">Transaction #${transaction.id}</div>
                        </div>
                        <div style="font-weight: 600; color: #28a745;">UGX ${amount}</div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }

        // Load balance information
        function loadBalanceInfo(tenantId) {
            $.ajax({
                url: '../api/get_tenant_balance.php',
                type: 'GET',
                data: { tenant_id: tenantId },
                success: function(response) {
                    currentBalance = response.total_balance || 0;
                    paidThisMonth = ((response.balance_due + response.balance_bf) - response.total_balance) || 0;
                    updateSummary();
                },
                error: function(xhr, status, error) {
                    console.error('Error loading balance info:', error);
                    currentBalance = 0;
                    paidThisMonth = 0;
                    updateSummary();
                }
            });
        }

        // Update payment summary
        function updateSummary() {
            const paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
            const remainingBalance = Math.max(0, currentBalance - paymentAmount);
            
            document.getElementById('summaryTotalBalance').textContent = 'UGX ' + new Intl.NumberFormat().format(currentBalance);
            document.getElementById('summaryPaidThisMonth').textContent = 'UGX ' + new Intl.NumberFormat().format(paidThisMonth);
            document.getElementById('summaryNewPayment').textContent = 'UGX ' + new Intl.NumberFormat().format(paymentAmount);
            document.getElementById('summaryRemainingBalance').textContent = 'UGX ' + new Intl.NumberFormat().format(remainingBalance);
            
            // Update color based on remaining balance
            const remainingElement = document.getElementById('summaryRemainingBalance');
            if (remainingBalance === 0) {
                remainingElement.style.color = '#28a745';
            } else if (remainingBalance < currentBalance) {
                remainingElement.style.color = '#ffc107';
            } else {
                remainingElement.style.color = '#dc3545';
            }
        }

        // Real-time summary update
        document.getElementById('paymentAmount').addEventListener('input', updateSummary);

        // Submit payment
        document.getElementById('submitPaymentBtn').addEventListener('click', function() {
            const paymentAmount = document.getElementById('paymentAmount').value;
            const paymentDate = document.getElementById('paymentDate').value;
            
            if (!paymentAmount) {
                alert('Please enter a valid payment amount');
                return;
            }
            
            if (!paymentDate) {
                alert('Please select a payment date');
                return;
            }
            
            if (confirm(`Confirm payment of UGX ${new Intl.NumberFormat().format(paymentAmount)} for this tenant?`)) {
                processPayment();
            }
        });

        // Process payment
        function processPayment() {
            const formData = new FormData(document.getElementById('paymentForm'));

            $.ajax({
                url: '../api/postpayment.php',
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false,  
                success: function(response) {
                            alert('Payment processed successfully!');
                            closePaymentModal();
                            location.reload();
                            // Refresh the tenant modal to show updated balance
                            if (typeof TReport === 'function') {
                                TReport(currentTenantId);
                            }
                },
                error: function(xhr, status, error) {
                    console.error('Payment error:', error);
                    alert('Error processing payment. Please try again.');
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
        function clearTenantModal() {
            document.getElementById('tid').value = '';
            document.getElementById('tname').value = '';
            document.getElementById('tcontact').value = '';
            document.getElementById('tlocation').value = '';
            document.getElementById('tbalance').value = '';
            document.getElementById('troom').value = '';
            document.getElementById('tlandlord').value = '';
            document.getElementById('tamount').value = '';
            document.getElementById('tdate').value = '';

            // Reset status badge too
            const badge = document.getElementById('status-badge');
            badge.textContent = 'Pending';
            badge.className = 'status-badge pending';

            // Reset transaction history link to default
            document.getElementById('thistory').setAttribute('href', 'transhistry.php?tenant=');
        }
    </script>
</body>
</html>