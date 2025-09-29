<?php 
require "Vhelper.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Payment Report - Mainstay Real Estate</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #2980b9;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
            --success-color: #27ae60;
            --warning-color: #e67e22;
            --danger-color: #e74c3c;
            --text-color: #333;
            --text-light: #666;
            --company-color: #1a5276;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            color: var(--text-color);
            background-color: #f5f7fa;
            line-height: 1.5;
        }
        
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* Enhanced Company Header */
        .company-header {
            background: linear-gradient(135deg, var(--company-color), #2c3e50);
            color: white;
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 4px solid var(--secondary-color);
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
            letter-spacing: 0.5px;
        }
        
        .company-tagline {
            font-size: 14px;
            opacity: 0.9;
            margin: 0 0 15px 0;
            font-weight: 300;
        }
        
        .company-contact {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 13px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .contact-icon {
            font-size: 14px;
        }
        
        .report-meta {
            text-align: right;
            min-width: 250px;
        }
        
        .report-title {
            font-size: 22px;
            margin: 0 0 8px 0;
            font-weight: 600;
        }
        
        .report-subtitle {
            font-size: 14px;
            opacity: 0.9;
            margin: 0 0 10px 0;
        }
        
        .report-date {
            font-size: 13px;
            opacity: 0.9;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            display: inline-block;
        }
        
        /* Tenant Information Section */
        .tenant-section {
            padding: 25px 30px;
            background: var(--light-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .tenant-info h1 {
            margin: 0 0 15px 0;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .tenant-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .tenant-detail {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .detail-value {
            font-size: 15px;
            font-weight: 500;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 25px 30px;
            background: var(--light-bg);
        }
        
        .summary-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--secondary-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .summary-card.pending {
            border-left-color: var(--warning-color);
        }
        
        .summary-card.negative {
            border-left-color: var(--danger-color);
        }
        
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
            color: var(--text-light);
            font-weight: 500;
        }
        
        .summary-card .amount {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        
        .summary-card .amount.positive {
            color: var(--success-color);
        }
        
        .summary-card .amount.negative {
            color: var(--danger-color);
        }
        
        .transactions-section {
            padding: 0 30px 30px;
        }
        
        .section-title {
            font-size: 20px;
            margin: 25px 0 15px 0;
            font-weight: 600;
            color: var(--primary-color);
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        
        th {
            background-color: var(--light-bg);
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-color);
            border-bottom: 2px solid var(--border-color);
        }
        
        td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
        }
        
        tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .transaction-id {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .amount-cell {
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-paid {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: var(--text-light);
            font-style: italic;
        }
        
        .report-footer {
            padding: 20px 30px;
            background: var(--light-bg);
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--text-light);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--accent-color);
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-color);
        }
        
        .btn-outline:hover {
            background: var(--light-bg);
        }
        
        @media (max-width: 768px) {
            .company-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .report-meta {
                text-align: left;
                min-width: auto;
            }
            
            .company-contact {
                flex-direction: column;
                gap: 10px;
            }
            
            .summary-cards {
                grid-template-columns: 1fr;
            }
            
            .transactions-section {
                padding: 0 15px 20px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            .report-footer {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .report-container {
                box-shadow: none;
            }
            
            .action-buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Enhanced Company Header -->
        <div class="company-header">
            <div class="company-info">
                <h1 class="company-name">Mainstay Real Estate Solutions Ltd.</h1>
                <p class="company-tagline">Your Trusted Partner in Property Management</p>
                <div class="company-contact">
                    <div class="contact-item">
                        <span class="contact-icon">📍</span>
                        <span>Abaita Ababiri, Entebbe, Uganda</span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <span>+256 773 897809</span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">📞</span>
                        <span>+256 753 040496</span>
                    </div>
                    <div class="contact-item">
                        <span class="contact-icon">✉️</span>
                        <span>info@mainstayrealestate.com</span>
                    </div>
                </div>
            </div>
            <div class="report-meta">
                <h2 class="report-title">Tenant Payment Report</h2>
                <p class="report-subtitle">Comprehensive Financial Summary</p>
                <p class="report-date">Generated on: <span id="datetime"></span></p>
            </div>
        </div>
        
        <!-- Tenant Information Section -->
        <div class="tenant-section">
            <div class="tenant-info">
                <?php 
                if (isset($_GET['tid'])) {
                    $tenant_id = $_GET['tid'];
                    $tenant = getTenant($tenant_id);
                    if ($tenant) {
                        $tenant_data = json_decode($tenant, true);
                        echo "<h1>{$tenant_data['name']}</h1>";
                        echo "<div class='tenant-details'>";
                        echo "<div class='tenant-detail'>";
                        echo "<div class='detail-label'>Tenant ID</div>";
                        echo "<div class='detail-value'>{$tenant_id}</div>";
                        echo "</div>";
                        echo "<div class='tenant-detail'>";
                        echo "<div class='detail-label'>Contact</div>";
                        echo "<div class='detail-value'>{$tenant_data['contact']}</div>";
                        echo "</div>";
                        echo "<div class='tenant-detail'>";
                        echo "<div class='detail-label'>Location</div>";
                        echo "<div class='detail-value'>{$tenant_data['location']}</div>";
                        echo "</div>";
                        echo "<div class='tenant-detail'>";
                        echo "<div class='detail-label'>Report Period</div>";
                        if (isset($_GET['tid'])) {
                            $transactions = getTransactions($_GET['tid']);
                            if ($transactions && count($transactions) > 0) {
                                // Sort transactions by date ascending to find the oldest one
                                usort($transactions, function($a, $b) {
                                    return strtotime($a['date_paid']) - strtotime($b['date_paid']);
                                });

                                $oldestDate = $transactions[0]['date_paid'];
                                echo "<div class='detail-value'>Since " . date('F Y', strtotime($oldestDate)) . "</div>";
                            } else {
                                echo "<div class='detail-value'>No transactions</div>";
                            }}
                        echo "</div>";
                        echo "<div class='tenant-detail'>";
                        echo "<div class='detail-label'>Room Amount</div>";
                        echo "<div class='detail-value'> UGx " . number_format(($balance_due + $balance_bf) . $tenant_data['amount'])."</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
        
        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Payments</h3>
                <?php
                if (isset($_GET['tid'])) {
                    $totalAmount = 0;
                    $transactions = getTransactions($_GET['tid']);
                    if ($transactions) {
                        foreach ($transactions as $transaction) {
                            $totalAmount += $transaction['amount'];
                        }
                    }
                    echo "<p class='amount positive'>UGx " . number_format($totalAmount) . "</p>";
                }
                ?>
            </div>
            <div class="summary-card pending">
                <h3>Pending Balance</h3>
                <?php 
                if (isset($tenant) && $tenant) {
                    $tenant_data = json_decode($tenant, true);
                    $balanceClass = ($tenant_data['balance'] > 0) ? 'negative' : 'positive';
                    $cardClass = ($tenant_data['balance'] > 0) ? 'negative' : 'pending';
                    echo "<p class='amount {$balanceClass}'>UGx " . number_format($tenant_data['balance']) . "</p>";
                }
                ?>
            </div>
            <div class="summary-card">
                <h3>Total Transactions</h3>
                <?php
                if (isset($_GET['tid'])) {
                    $transactionCount = $transactions ? count($transactions) : 0;
                    echo "<p class='amount'>" . $transactionCount . "</p>";
                }
                ?>
            </div>
        </div>
        
        <div class="transactions-section">
            <h2 class="section-title">Transaction History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Payment Date</th>
                        <th>Landlord</th>
                        <th>Room ID</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET['tid']) && $transactions) {
                        // Sort the transactions by date_paid in descending order (newest first)
                        usort($transactions, function($a, $b) {
                            return strtotime($b['date_paid']) - strtotime($a['date_paid']);
                        });
                
                        foreach ($transactions as $transaction) {
                            $room = getRoom($transaction['room'], $rooms);
                            $landlord = getLandlord($room['landlord'], $landlords);
                            echo "<tr>";
                            echo "<td><span class='transaction-id'>#{$transaction['id']}</span></td>";
                            echo "<td>{$transaction['date_paid']}</td>";
                            echo "<td>{$landlord['name']}</td>";
                            echo "<td>#{$transaction['room']}</td>";
                            echo "<td class='amount-cell'>UGx " . number_format($transaction['amount']) . "</td>";
                            echo "<td>Collection</td>";
                            echo "<td><span class='status-badge status-paid'>Paid</span></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='no-data'>No transactions found for this tenant</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="report-footer">
            <div class="report-notes">
                <p>This is an automatically generated report. For any discrepancies, please contact the administration.</p>
            </div>
            <div class="action-buttons">
                <button class="btn btn-primary" onclick="generatePDF()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/html2canvas.min.js"></script>
    <script src="js/jspdf.umd.min.js"></script>

    <script>
        function updateDateTime() {
            let now = new Date();
            let options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            let formattedDateTime = now.toLocaleDateString('en-US', options);
            document.getElementById("datetime").textContent = formattedDateTime;
        }
        updateDateTime();

        function generatePDF() {
            const { jsPDF } = window.jspdf;
            let doc = new jsPDF('p', 'mm', 'a4');
            
            
            // Get the report container
            const element = document.querySelector('.report-container');
            const actions = document.querySelector('.action-buttons');

            // Hide the button section before generating PDF
            actions.style.display = 'none';

            // Options for html2canvas
            const options = { 
                scale: 2,
                useCORS: true,
                logging: false,
                backgroundColor: '#ffffff'
            };
            
            html2canvas(element, options).then(canvas => {
                const imgData = canvas.toDataURL('image/jpeg', 0.9);
                const imgWidth = 210; // A4 width in mm
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                let position = -1; // Start position after header
                let pageHeight = 295; // A4 height in mm
                let heightLeft = imgHeight;
                let pageNumber = 1;
                const totalPages = Math.ceil(imgHeight / (pageHeight - position));
                
                // Add first page
                doc.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                heightLeft -= (pageHeight - position);
                
                // Add page number for first page
                doc.setFontSize(10);
                doc.setTextColor(150, 150, 150);
                doc.text(`Page ${pageNumber} of ${totalPages}`, 105, 285, { align: 'center' });
                
                // Add additional pages if needed
                while (heightLeft > 0) {
                    position = -((pageHeight) +32- heightLeft); // Calculate position for next page
                    doc.addPage();
                    pageNumber++;
                    
                    doc.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
                    
                    // Add page number
                    doc.setFontSize(10);
                    doc.setTextColor(150, 150, 150);
                    doc.text(`Page ${pageNumber} of ${totalPages}`, 105, 285, { align: 'center' });
                    
                    heightLeft -= pageHeight;
                }
                
                // Save the PDF
                doc.save('Mainstay-Tenant-Report-' + new Date().toISOString().slice(0, 10) + '.pdf');

                // Show the button again after PDF is generated
                actions.style.display = 'flex';
            }).catch(error => {
                console.error('PDF generation error:', error);

                // Ensure the button is shown again even on error
                actions.style.display = 'flex';
            });
        }
    </script>
</body>
</html>