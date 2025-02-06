<?php 
 require "Vhelper.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-inline: 10%;
            display: flex;
            margin-block: 20px;
            flex-direction: column;
            p,h2{
                font-weight: normal;
                margin: 5px;
            }
            h2{
                font-size: 2.5rem;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            
        }
        th {
            background-color: #9197b341;
        }
        tr:nth-child(even) {
            background-color: #ffffff;
        }
        tbody{
            tr:nth-child(odd) {
            background-color: #d9d9d92f;
        }
        }
        .cards{
            display: flex;
            gap: 20px;
            margin-block: 10px;
            .card{
                background-color: rgba(127, 255, 212, 0.418);
                width: fit-content;
                padding: 15px;
                h4{
                    color: green;
                    margin: 0;
                    font-weight: normal;
                }
                p{
                    margin: 0;
                    color: black;
                }
            }
        }
        .date{
            position: absolute;
            right: 10%;
        }
    </style>
</head>
<body>
    <?php 
    if (isset($_GET['tid'])) {
        $tenant_id = $_GET['tid']; // Get tenant ID from the query string
        $tenant= getTenant($tenant_id);
        if ($tenant) {
            $tenant_data = json_decode($tenant, true);
            echo "<h2>{$tenant_data['name']}</h2>";
            echo "<p>{$tenant_data['contact']}</p>";
            echo "<p>{$tenant_data['location']}</p>";
        }
    } else {
    
    }
    
    ?>
    <div class="date">
        <p><span id="datetime"></span></p>
    </div>
    <div class="cards">
        <div class="card">
        <?php
if (isset($_GET['tid'])) {
    $totalAmount = 0;
    foreach (getTransactions($_GET['tid']) as $transaction) {
        $totalAmount += $transaction['amount'];
    }
    echo "<h4>UGx  {$totalAmount}</h4>";
}
?>
            <p>Total Payment</p>
        </div>
        <div class="card">
            <?php 
            if ($tenant) {
                $tenant_data = json_decode($tenant, true);
                echo "<h4>UGx {$tenant_data['balance']}</h4>";
            }
            ?>
            <p>Pending Balance</p>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Payment Date</th>
                <th>Landlord</th>
                <th>Room ID</th>
                <th>Amount</th>
                <th>Transaction Type</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET['tid'])) {
                $yh= getTransactions($_GET['tid']);
                if ($yh) {
                    # code...
                    foreach ($yh as $transaction) {
                        $room =getRoom($transaction['room'],$rooms);
                        $landlord = getLandlord($room['landlord'], $landlords);
                        // Replace placeholder names and balance with data from JSON
                        echo "<tr>";
                        echo "<td> #{$transaction['id']}</td>";
                        echo "<td>{$transaction['date_paid']}</td>";
                        echo "<td>{$landlord['name']}</td>";
                        echo "<td>#{$transaction['room']}</td>";
                        echo "<td>UGx {$transaction['amount']}</td>";
                        echo "<td>Collection</td>";
                        echo "<td>Paid</td>";
                        echo "</tr>";
                    }
                }else{
                    echo "<tr style='background:#00000000;'>";
                        echo "<td >no transactions</td>";
                        echo "</tr>";
                }

            }
        // Loop through tenants and output the table rows
        
?>
            
            
        </tbody>
    </table>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        function updateDateTime() {
            let now = new Date();
            let formattedDateTime = now.toLocaleString();
            document.getElementById("datetime").textContent = formattedDateTime;
        }
        updateDateTime();

        function generatePDF() {
        const { jsPDF } = window.jspdf;
        let doc = new jsPDF('p', 'mm', 'a4');

        html2canvas(document.body, { scale: 1.5 }).then(canvas => {
            let imgData = canvas.toDataURL('image/jpeg', 1); // JPEG format with 60% quality
            let imgWidth = 210; // A4 width in mm
            let imgHeight = (canvas.height * imgWidth) / canvas.width;

            doc.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight);
            let pdfBlob = doc.output('blob');
            let url = URL.createObjectURL(pdfBlob);
            window.location.href = url; // Opens in new tab
        });
    }
//     $( document ).ready(function() {
//     generatePDF();
// });
    </script>

</body>
</html>
