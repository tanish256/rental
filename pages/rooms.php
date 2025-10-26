<?php 
require '../helpers/Vhelper.php';
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

            <div class="summary">
                
                <!-- ...................................summary.................................. -->
                <div class="sum">
                    <div class="circle">
                        <img src="../assets/profile-2user.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total Tenants</h3>
                        <h4><?php echo $ttenants?></h4>
                    <p>this month</p>
                    </div>
                </div>
    
                <div class="sum">
                    <div class="circle">
                        <img src="../assets/profile-tick.svg" alt="">
                    </div>
                    <div class="inf">
                        <h3>Total Landlords</h3>
                        <h4><?php echo $tlandlords?></h4>
                    <p>this month</p>
                    </div>
                </div>
    
                <div class="sum">
                    <div class="circle">
                        <img src="../assets/monitor.svg" alt="">
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
                        <th style='width:9%'>Room Id</th>
                        <th>Landlord</th>
                        <th style='width:19%'>Tenant</th>
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
                        $tenant = getTenantByRoom($room['id']);
                        $isOccupied = roomHasTenant($pdo, $room['id']);
                        $statusClass = $isOccupied ? 'status-active' : 'status-inactive';
                        $statusText = $isOccupied ? 'occupied' : 'vacant';
                        
                        echo "<tr class='room-row' 
                            data-room-id='{$room['id']}'
                            data-room-location='{$room['location']}'
                            data-room-amount='{$room['amount']}'
                            data-landlord-name='{$landlord['name']}'
                            data-tenant-name='{$tenant}'
                            data-is-occupied='" . ($isOccupied ? 'true' : 'false') . "'
                            data-status='{$statusText}'>";
                        
                        echo "<td style='width:9%'>#{$room['id']}</td>";
                        echo "<td>{$landlord['name']}</td>";
                        echo "<td style='width:19%'>{$tenant}</td>";
                        echo "<td>{$room['location']}</td>";
                        echo "<td>ugx " . number_format($room['amount'], 0, '.', ',') . "</td>";
                        echo "<td class='{$statusClass}'><div>{$statusText}</div></td>";
                        echo "</tr>";
                    }
                ?>
                    
                    
                </tbody>
            </table>
            </div>
            <!-- ----------------------------------table-------------------------------------------- -->

        </div>
    </div>

    <!-- Delete Room Modal -->
    <div class="Tparent" id="deleteRoomModal">
        <div class="card">
            <button class="x" id="closeDeleteModal">&times;</button>
            <div class="header">
                <h2>Room Details</h2>
                <div class="subtitle">Current room information</div>
            </div>
            <div class="content">
                <div class="room-info" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: #333;">Room Information</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <strong>Room ID:</strong>
                            <span id="deleteRoomId">-</span>
                        </div>
                        <div>
                            <strong>Location:</strong>
                            <span id="deleteRoomLocation">-</span>
                        </div>
                        <div>
                            <strong>Landlord:</strong>
                            <span id="deleteRoomLandlord">-</span>
                        </div>
                        <div>
                            <strong>Monthly Rent:</strong>
                            <span id="deleteRoomAmount">-</span>
                        </div>
                        <div>
                            <strong>Current Tenant:</strong>
                            <span id="deleteRoomTenant">-</span>
                        </div>
                        <div>
                            <strong>Status:</strong>
                            <span id="deleteRoomStatus">-</span>
                        </div>
                    </div>
                </div>
                <!-- Confirmation Section -->
                <div class="confirmation-section" style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px;">
                    <h4 style="margin: 0 0 10px 0; color: #856404;">⚠️ Confirm Deletion</h4>
                    <p  id="occupiedWarning" style="display: none; margin: 0 0 15px 0; color: #856404;">
                        This room currently has a tenant. Deleting it will also remove the tenant assignment. 
                        Consider transferring the tenant to another room first.
                    </p>
                    <p style="margin: 0 0 15px 0; color: #856404;">
                        This action cannot be undone. All room information will be permanently deleted.
                    </p>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-outline" id="cancelDeleteBtn">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="icon">🗑️</span> Delete Room
                </button>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script>
        // Room deletion functionality
        let currentRoomId = null;
        let currentRoomData = null;

        // Add click event to room rows
        document.addEventListener('DOMContentLoaded', function() {
            const roomRows = document.querySelectorAll('.room-row');
            <?php if($_SESSION['role'] != 'user') {
                echo "roomRows.forEach(row => {
                row.addEventListener('click', function(e) {
                    const roomId = this.getAttribute('data-room-id');
                    const roomData = getRoomDataFromRow(this);
                    openDeleteModal(roomId, roomData);
                });
            });"; }?>

            // Modal event listeners
            document.getElementById('closeDeleteModal').addEventListener('click', closeDeleteModal);
            document.getElementById('cancelDeleteBtn').addEventListener('click', closeDeleteModal);
            document.getElementById('confirmDeleteBtn').addEventListener('click', deleteRoom);

            // Close modal when clicking outside
            document.getElementById('deleteRoomModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });
        });

        // Get room data from table row
        function getRoomDataFromRow(row) {
            const cells = row.cells;
            return {
                id: row.getAttribute('data-room-id'),
                location: row.getAttribute('data-room-location'),
                amount: row.getAttribute('data-room-amount'),
                landlord_name: row.getAttribute('data-landlord-name'),
                tenant_name: row.getAttribute('data-tenant-name') || 'No tenant',
                is_occupied: row.getAttribute('data-is-occupied') === 'true',
                status: row.getAttribute('data-status'),
                // Additional data from table cells
                roomIdDisplay: cells[0].textContent,
                landlordDisplay: cells[1].textContent,
                tenantDisplay: cells[2].textContent,
                locationDisplay: cells[3].textContent,
                amountDisplay: cells[4].textContent,
                statusDisplay: cells[5].textContent.trim()
            };
        }

        // Open delete modal
        function openDeleteModal(roomId, roomData) {
            currentRoomId = roomId;
            currentRoomData = roomData;
            
            // Display room data
            displayRoomData(roomData);
            
            // Check and show warnings
            checkWarnings(roomData);
            
            // Show modal
            document.getElementById('deleteRoomModal').classList.add('active');
        }

        // Close delete modal
        function closeDeleteModal() {
            document.getElementById('deleteRoomModal').classList.remove('active');
            currentRoomId = null;
            currentRoomData = null;
        }

        // Display room data in modal
        function displayRoomData(roomData) {
            document.getElementById('deleteRoomId').textContent = '#' + roomData.id;
            document.getElementById('deleteRoomLocation').textContent = roomData.location;
            document.getElementById('deleteRoomLandlord').textContent = roomData.landlord_name;
            document.getElementById('deleteRoomAmount').textContent = roomData.amountDisplay;
            document.getElementById('deleteRoomTenant').textContent = roomData.tenant_name === 'No tenant' ? 'No tenant' : roomData.tenant_name;
            document.getElementById('deleteRoomStatus').textContent = roomData.is_occupied ? 'Occupied' : 'Vacant';
            
            // Style status
            const statusElement = document.getElementById('deleteRoomStatus');
            if (roomData.is_occupied) {
                statusElement.style.color = '#28a745';
                statusElement.style.fontWeight = '600';
            } else {
                statusElement.style.color = '#6c757d';
            }
        }

        // Check and display warnings
        function checkWarnings(roomData) {
            const occupiedWarning = document.getElementById('occupiedWarning');

            // Show occupied warning if room has tenant
            if (roomData.is_occupied) {
                occupiedWarning.style.display = 'block';
            } else {
                occupiedWarning.style.display = 'none';
            }
        }

        // Delete room
        function deleteRoom() {
            if (!currentRoomId) {
                alert('No room selected');
                return;
            }

            if (!confirm('Are you absolutely sure you want to delete this room? This action cannot be undone.')) {
                return;
            }


            $.ajax({
                url: '../api/delete_room.php',
                type: 'POST',
                data: { 
                    room_id: currentRoomId,
                    confirm: 'DELETE'
                },
                success: function(response) {
                        if (response.success) {
                            //alert('Room deleted successfully!');
                            // Remove the row from the table
                            const row = document.querySelector(`.room-row[data-room-id="${currentRoomId}"]`);
                            if (row) {
                                row.remove();
                            }
                            closeDeleteModal();
                            // Show success message
                            showSuccessMessage('Room deleted successfully!');
                        } else {
                            alert('Error: ' + (response.message || 'Failed to delete room'));
                            deleteBtn.disabled = false;
                            deleteBtn.innerHTML = '<span class="icon">🗑️</span> Delete Room';
                        }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', error);
                    alert('Error deleting room. Please try again.');
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = '<span class="icon">🗑️</span> Delete Room';
                }
            });
        }

        // Show success message
        function showSuccessMessage(message) {
            // Create a temporary success message
            const successMsg = document.createElement('div');
            successMsg.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                z-index: 10000;
                font-weight: 500;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            `;
            successMsg.textContent = message;
            document.body.appendChild(successMsg);
            
            // Remove after 3 seconds
            setTimeout(() => {
                successMsg.remove();
            }, 3000);
        }
    </script>
    <script src="../js/filter.js"></script>
</body>
</html>