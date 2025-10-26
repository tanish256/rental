<?php 
 require "../helpers/auth-check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Management System</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Additional styles for the improved interface */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .action-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .primary-btn {
            padding: 7px 12px;
            background-color: #4a6cf7;
            color: white;
            border: none;
            border-radius: 4px;
        }
        
        .secondary-btn {
            border-radius: 4px;
            background-color: #f0f0f0;
            color: #333;
        }
        
        .workflow-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .workflow-step {
            flex: 1;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .workflow-step h3 {
            margin-top: 0;
            color: #4a6cf7;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .rooms-list {
            max-height: 60vh;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            background-color: white;
        }
        
        .room-item {
            padding: 8px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }
        
        .room-item:hover {
            background-color: #f0f0f0;
        }
        
        .room-item.selected {
            background-color: #e6f0ff;
            border-left: 3px solid #4a6cf7;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #e6f7e6;
            color: #2e7d32;
        }
        
        .status-inactive {
            background-color: #ffe6e6;
            color: #c62828;
        }
        
        .status-vacant {
            background-color: #fff3e0;
            color: #ef6c00;
        }
        
        .loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loader.active {
            display: flex;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4a6cf7;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e0e0e0;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div class="loader" id="loader">
        <div class="spinner"></div>
    </div>

    <div class="root">
        <?php include '../components/sidebar.php'; ?>
        <div class="dashmain">
            <div class="tabs-container">
                <div class="tabs">
                    <button class="tab-button active" data-tab="landlords">Landlords</button>
                    <button class="tab-button" data-tab="rooms">Rooms</button>
                    <button class="tab-button" data-tab="tenants">Tenants</button>
                </div>

                <!-- LANDLORDS TAB -->
                <div id="landlords" class="tab-content active">
                    <div class="dashboard-header">
                        <div class="headers">
                            <h1>Landlords</h1>
                            <p>Manage property owners and their information</p>
                        </div>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="showAddLandlordModal()">Add New Landlord</button>
                        </div>
                    </div>
                        <style>
                            #search {
                                width: 50vw;
                                margin-bottom: 15px;
                            }
                            th, td {
                                width: 24%;
                            }
                        </style>
                    <div class="tablecard">
                         <div class="tops">
                            <div class="right">
                                <input type="text" id="search" placeholder="Search landlords..." onkeyup="filterTablel()">
                            </div>
                        </div>
                        <table id="landlordTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($landlords as $landlord) {
                                echo "<tr>";
                                echo "<td>{$landlord['name']}</td>";
                                echo "<td class='email'>{$landlord['email']}</td>";
                                echo "<td>{$landlord['contact']}</td>";
                                echo "<td>{$landlord['location']}</td>";
                                echo "<td class='actions'>";
                                echo "<button class='secondary-btn' onclick='editLandlord({$landlord['id']})'>Edit</button>";
                                echo "<button class='secondary-btn' onclick='viewLandlordRooms({$landlord['id']})'>View Rooms</button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>   
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ROOMS TAB -->
                <div id="rooms" class="tab-content">
                    <div class="dashboard-header">
                        <div class="headers">
                            <h1>Rooms Management</h1>
                            <p>Add and manage rental rooms</p>
                        </div>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="showAddRoomModal()">Add New Room</button>
                        </div>
                    </div>
                    
                    <div class="workflow-container">
                        <div class="workflow-step">
                            <h3>Step 1: Select Landlord</h3>
                            <div class="form-group">
                                <label for="landlord-select">Choose a Landlord:</label>
                                <select id="landlord-select" class="form-control" onchange="loadLandlordRooms()">
                                    <option value="">-- Select Landlord --</option>
                                    <?php
                                    foreach ($landlords as $landlord) {
                                        echo "<option value='{$landlord['id']}'>{$landlord['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="workflow-step">
                            <h3>Step 2: Manage Rooms</h3>
                            <div id="rooms-container">
                                <p>Select a landlord to view their rooms</p>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- TENANTS TAB -->
                <div id="tenants" class="tab-content">
                    <div class="dashboard-header">
                        <div class="headers">
                            <h1>Tenants</h1>
                            <p>Manage tenant information and room assignments</p>
                        </div>
                        <div class="action-buttons">
                            <button class="primary-btn" onclick="showAddTenantModal()">Add New Tenant</button>
                        </div>
                    </div>
                    
                    <div class="workflow-container">
                        <div class="workflow-step">
                            <h3>Step 1: Select Landlord</h3>
                            <div class="form-group">
                                <label for="tenant-landlord-select">Choose a Landlord:</label>
                                <select id="tenant-landlord-select" class="form-control" onchange="loadAvailableRooms()">
                                    <option value="">-- Select Landlord --</option>
                                    <?php
                                    foreach ($landlords as $landlord) {
                                        echo "<option value='{$landlord['id']}'>{$landlord['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="workflow-step">
                            <h3>Step 2: Select Available Room</h3>
                            <div id="available-rooms-container">
                                <p>Select a landlord to view available rooms</p>
                            </div>
                        </div>
                        
                        <div class="workflow-step">
                            <h3>Step 3: Assign Tenant</h3>
                            <div id="tenant-assignment-container">
                                <p>Select a room to assign a tenant</p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Add Landlord Modal -->
    <div class="modal" id="addLandlordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Landlord</h2>
                <button class="close-modal" onclick="closeModal('addLandlordModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addLandlordForm">
                    <div class="form-group">
                        <label for="landlordName">Full Name</label>
                        <input type="text" id="landlordName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="landlordEmail">Email</label>
                        <input type="email" id="landlordEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="landlordPhone">Phone Number</label>
                        <input type="text" id="landlordPhone" name="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="landlordLocation">Location</label>
                        <input type="text" id="landlordLocation" name="location" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="secondary-btn" onclick="closeModal('addLandlordModal')">Cancel</button>
                <button class="primary-btn" onclick="submitLandlordForm()">Save Landlord</button>
            </div>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal" id="addRoomModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Room</h2>
                <button class="close-modal" onclick="closeModal('addRoomModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addRoomForm">
                    <div class="form-group">
                        <label for="roomLandlord">Landlord</label>
                        <select id="roomLandlord" name="landlord" class="form-control" required>
                            <option value="">-- Select Landlord --</option>
                            <?php
                            foreach ($landlords as $landlord) {
                                echo "<option value='{$landlord['id']}'>{$landlord['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="roomLocation">Location/Address</label>
                        <input type="text" id="roomLocation" name="location" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="roomAmount">Monthly Rent (UGX)</label>
                        <input type="number" id="roomAmount" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="roomCondition">Condition/Description</label>
                        <input type="text" id="roomCondition" name="condition" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="secondary-btn" onclick="closeModal('addRoomModal')">Cancel</button>
                <button class="primary-btn" onclick="submitRoomForm()">Save Room</button>
            </div>
        </div>
    </div>

    <!-- Add Tenant Modal -->
    <div class="modal" id="addTenantModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Tenant</h2>
                <button class="close-modal" onclick="closeModal('addTenantModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addTenantForm">
                    <div class="form-group">
                        <label for="tenantName">Full Name</label>
                        <input type="text" id="tenantName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tenantPhone">Phone Number</label>
                        <input type="text" id="tenantPhone" name="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="tenantRoom">Assign to Room</label>
                        <select id="tenantRoom" name="room_id" class="form-control" required>
                            <option value="">-- Select Room --</option>
                            <?php
                            // Show only vacant rooms
                            foreach ($rooms as $room) {
                                if (!roomHasTenant($pdo, $room['id'])) {
                                    $landlord = getLandlord($room['landlord'], $landlords);
                                    echo "<option value='{$room['id']}'>#{$room['id']} - {$room['location']} ({$landlord['name']}) - ({$room['amount']})</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="secondary-btn" onclick="closeModal('addTenantModal')">Cancel</button>
                <button class="primary-btn" onclick="submitTenantForm()">Save Tenant</button>
            </div>
        </div>
    </div>
    <!-- Edit Room Modal -->
    <div class="modal" id="editRoomModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Room</h2>
                <button class="close-modal" onclick="closeModal('editRoomModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editRoomForm">
                    <input type="hidden" id="editRoomId" name="id">
                    <div class="form-group">
                        <label for="editRoomLandlord">Landlord</label>
                        <input type="text" id="editRoomLandlord" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editRoomLocation">Location</label>
                        <input type="text" id="editRoomLocation" name="location" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="editRoomAmount">Monthly Rent (UGX)</label>
                        <input type="number" id="editRoomAmount" name="amount" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editRoomCondition">Description</label>
                        <input type="text" id="editRoomCondition" name="condition" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="secondary-btn" onclick="deleteRoom()">Delete Room</button>
                <button class="primary-btn" onclick="submitEditRoomForm()">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Edit Landlord Modal -->
    <div class="modal" id="editLandlordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Landlord</h2>
                <button class="close-modal" onclick="closeModal('editLandlordModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editLandlordForm">
                    <input type="hidden" id="editLandlordId" name="id">
                    <div class="form-group">
                        <label for="editLandlordName">Full Name</label>
                        <input type="text" id="editLandlordName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editLandlordEmail">Email</label>
                        <input type="email" id="editLandlordEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editLandlordPhone">Phone Number</label>
                        <input type="text" id="editLandlordPhone" name="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editLandlordLocation">Location</label>
                        <input type="text" id="editLandlordLocation" name="location" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="secondary-btn" onclick="closeModal('editLandlordModal')">Cancel</button>
                <button class="primary-btn" onclick="submitEditLandlordForm()">Save Changes</button>
            </div>
        </div>
    </div>



    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/filter.js"></script>
    <script>
        // Open Edit Room Modal and populate values
        function editRoom(id) {
            // Find the room object from the loaded rooms
            const landlordId = document.getElementById('landlord-select').value;
            const roomsList = window.loadedRooms || []; // store loaded rooms globally when fetching

            const room = roomsList.find(r => r.id === id);
            if (!room) {

                showFailMessage("Room not found in current selection");
                return;
            }

            // Populate modal fields
            $('#editRoomId').val(room.id);
            $('#editRoomLandlord').val(room.landlord_name);
            $('#editRoomLocation').val(room.location);
            $('#editRoomAmount').val(room.amount);
            $('#editRoomCondition').val(room.roomcondition || '');
            $('#editRoomRemarks').val(room.remarks || '');

            // Show modal
            $('#editRoomModal').addClass('active');
        }

        // Submit edited room
        function submitEditRoomForm() {
            const formData = $('#editRoomForm').serialize();
            showLoader();

            $.ajax({
                url: "../api/editroom.php",
                type: "POST",
                data: formData,
                success: function(response) {
                    hideLoader();
                    const res = typeof response === "string" ? JSON.parse(response) : response;

                    if (res.error) {
                        showFailMessage("Error: " + res.error);
                    } else {
                        showSuccessMessage(res.message);
                        closeModal('editRoomModal');
                        loadLandlordRooms(); // Refresh rooms list
                    }
                },
                error: function(xhr) {
                    hideLoader();
                    showFailMessage("Error saving room: refresh and try again.");
                }
            });
        }

        // Delete room
        function deleteRoom() {
            if (!confirm("Are you sure you want to delete this room?")) return;

            const roomId = $('#editRoomId').val();
            showLoader();

            $.ajax({
                url: "../api/editroom.php",
                type: "POST",
                data: { id: roomId, del: 1 },
                success: function(response) {
                    hideLoader();
                    showSuccessMessage(response.message);
                    closeModal('editRoomModal');
                    loadLandlordRooms(); // Refresh rooms list
                },
                error: function(xhr) {
                    hideLoader();
                    showFailMessage("Error deleting room: refresh and try again.");
                }
            });
        }

    </script>
    <script>
        // Tab switching functionality
        document.querySelectorAll(".tab-button").forEach(button => {
            button.addEventListener("click", () => {
                // Remove active class from all buttons and content
                document.querySelectorAll(".tab-button").forEach(btn => btn.classList.remove("active"));
                document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));
                
                // Activate the clicked tab and its content
                button.classList.add("active");
                const tabId = button.getAttribute("data-tab");
                document.getElementById(tabId).classList.add("active");
            });
        });

        // Modal functions
        function showAddLandlordModal() {
            document.getElementById('addLandlordModal').classList.add('active');
        }

        function showAddRoomModal() {
            document.getElementById('addRoomModal').classList.add('active');
        }

        function showAddTenantModal() {
            document.getElementById('addTenantModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].classList.remove('active');
                }
            }
        }

        // Form submission functions
        function submitLandlordForm() {
            const formData = new FormData(document.getElementById('addLandlordForm'));
            showLoader();
            
            // AJAX call to submit landlord data
            $.ajax({
                url: "../api/RegisterLandlord.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    hideLoader();
                    closeModal('addLandlordModal');
                    showSuccessMessage("Landlord added successfully");
                    // Refresh the page or update the table
                    setTimeout(() => {
                        location.reload(); // Or call a function to refresh the table dynamically
                    }, 1500);
                },
                error: function(xhr, status, error){
                    hideLoader();
                    showFailMessage("Error adding landlord: refresh and try again.");
                }
            });
        }

        function submitRoomForm() {
            const formData = new FormData(document.getElementById('addRoomForm'));
            showLoader();
            
            // AJAX call to submit room data
            $.ajax({
                url: "../api/RegisterRoom.php", // You'll need to create this endpoint
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    hideLoader();
                    closeModal('addRoomModal');
                    showSuccessMessage("Room added successfully");
                    // Refresh the page or update the table
                    //location.reload();
                },
                error: function(xhr, status, error){
                    hideLoader();
                    showFailMessage("Error adding room: refresh and try again.");
                }
            });
        }

        function submitTenantForm() {
            const formData = new FormData(document.getElementById('addTenantForm'));
            showLoader();
            
            // AJAX call to submit tenant data
            $.ajax({
                url: "../api/RegisterTenant.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response){
                    hideLoader();
                    closeModal('addTenantModal');
                    showSuccessMessage("Tenant added successfully");
                    setTimeout(() => {
                        location.reload(); // Or call a function to refresh the table dynamically
                    }, 1500);
                },
                error: function(xhr, status, error){
                    hideLoader();
                    showFailMessage("Error adding tenant: ");
                }
            });
        }

        // Load rooms for selected landlord (Rooms tab)
        function loadLandlordRooms() {
            const landlordId = document.getElementById('landlord-select').value;
            if (!landlordId) {
                document.getElementById('rooms-container').innerHTML = '<p>Select a landlord to view their rooms</p>';
                return;
            }
            
            showLoader();
            
            // AJAX call to get landlord's rooms
            $.ajax({
                url: "../api/GetLandlordRooms.php",
                type: "GET",
                data: { landlord_id: landlordId },
                success: function(response){
                    hideLoader();

                    // If response is already an object, do not parse again
                    const res = typeof response === "string" ? JSON.parse(response) : response;
                    const rooms = res.rooms;  // <--- access the correct key
                    window.loadedRooms = rooms;

                    let html = '<h4>Rooms</h4>';
                    
                    if (rooms && rooms.length > 0) {
                        html += '<div class="rooms-list">';
                        rooms.forEach(room => {
                            const statusClass = room.tenant_id ? 'status-active' : 'status-vacant';
                            const statusText = room.tenant_id ? 'Occupied' : 'Vacant';
                            
                            html += `
                                <div class="room-item" onclick="editRoom(${room.id})" >
                                    <div><strong>#${room.id}</strong> - ${room.location}</div>
                                    <div>Rent: UGX ${Number(room.amount).toLocaleString()}</div>
                                    <div>Description: ${room.roomcondition || "not set"}</div>
                                    <div><span class="status-badge ${statusClass}">${statusText}</span></div>
                                </div>
                            `;
                        });
                        html += '</div>';
                    } else {
                        html += '<p>No rooms found for this landlord.</p>';
                    }
                    
                    document.getElementById('rooms-container').innerHTML = html;
                },
                error: function(xhr, status, error){
                    hideLoader();
                    showFailMessage("Error loading rooms: " + xhr.responseText);
                }
            });

        }

        // Load available rooms for selected landlord (Tenants tab)
        function loadAvailableRooms() {
            const landlordId = document.getElementById('tenant-landlord-select').value;
            if (!landlordId) {
                document.getElementById('available-rooms-container').innerHTML = '<p>Select a landlord to view available rooms</p>';
                document.getElementById('tenant-assignment-container').innerHTML = '<p>Select a room to assign a tenant</p>';
                return;
            }
            
            showLoader();
            
            // AJAX call to get available rooms for landlord
            $.ajax({
                url: "../api/GetLandlordRooms.php",
                type: "GET",
                data: { landlord_id: landlordId },
                success: function(response){
                    hideLoader();
                    const res = typeof response === "string" ? JSON.parse(response) : response;;
                    const rooms = res.rooms.filter(room => !room.tenant_id); // Only available rooms
                    console.log(rooms);

                    let html = '<h4>Available Rooms</h4>';
                    
                    if (rooms.length > 0) {
                        html += '<div class="rooms-list">';
                        rooms.forEach(room => {
                            html += `
                                <div class="room-item" onclick="selectRoomForTenant(${room.id}, '${room.location}', ${room.amount})">
                                    <div><strong>#${room.id}</strong> - ${room.location}</div>
                                    <div>Rent: UGX ${room.amount.toLocaleString()}</div>
                                </div>
                            `;
                        });
                        html += '</div>';
                    } else {
                        html += '<p>No available rooms found for this landlord.</p>';
                    }
                    
                    document.getElementById('available-rooms-container').innerHTML = html;
                    document.getElementById('tenant-assignment-container').innerHTML = '<p>Select a room to assign a tenant</p>';
                },
                error: function(xhr, status, error){
                    hideLoader();
                    showFailMessage("Error loading available rooms: " + xhr.responseText);
                }
            });
        }

        // Select a room for tenant assignment
        function selectRoomForTenant(roomId, location, amount) {
            // Remove selected class from all room items
            document.querySelectorAll('#available-rooms-container .room-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to clicked room
            event.target.closest('.room-item').classList.add('selected');
            
            // Show tenant assignment form
            const html = `
                <h4>Assign Tenant to Room #${roomId}</h4>
                <p><strong>Location:</strong> ${location}</p>
                <p><strong>Monthly Rent:</strong> UGX ${amount.toLocaleString()}</p>
                <form id="assignTenantForm">
                    <div class="form-group">
                        <label for="newTenantName">Tenant Name</label>
                        <input type="text" id="newTenantName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="newTenantPhone">Phone Number</label>
                        <input type="text" id="newTenantPhone" class="form-control" required>
                    </div>
                    <input type="hidden" id="selectedRoomId" value="${roomId}">
                    <button type="button" class="primary-btn" onclick="assignNewTenant()">Assign Tenant</button>
                </form>
            `;
            
            document.getElementById('tenant-assignment-container').innerHTML = html;
        }

        // Assign a new tenant to the selected room
        function assignNewTenant() {
            const tenantName = document.getElementById('newTenantName').value;
            const tenantPhone = document.getElementById('newTenantPhone').value;
            const roomId = document.getElementById('selectedRoomId').value;
            
            if (!tenantName || !tenantPhone) {
                showFailMessage('Please fill in all required fields');
                return;
            }
            
            showLoader();
            
            // AJAX call to assign tenant to room
            $.ajax({
                url: "../api/RegisterTenant.php",
                type: "POST",
                data: {
                    name: tenantName,
                    contact: tenantPhone,
                    room_id: roomId
                },
                success: function(response){
                    hideLoader();
                    showSuccessMessage('Tenant assigned successfully!');
                    setTimeout(() => {
                        location.reload(); // Or call a function to refresh the table dynamically
                    }, 1500);
                },
                error: function(xhr, status, error){
                    hideLoader();
                    showFailMessage('Failed to assign tenant.');
                }
            });
        }

        // Edit functions (simplified for example)
        function editLandlord(id) {
            // Find the row corresponding to this landlord
            const table = document.getElementById('landlordTable');
            const rows = table.querySelectorAll('tbody tr');
            let targetRow = null;

            rows.forEach(row => {
                // Assuming the first cell contains the name and actions button has the onclick id
                const editBtn = row.querySelector('button.secondary-btn[onclick*="editLandlord(' + id + ')"]');
                if (editBtn) {
                    targetRow = row;
                }
            });

            if (!targetRow) {
                showFailMessage("Landlord not found in table.");
                return;
            }

            // Extract data from the row
            const name = targetRow.cells[0].textContent.trim();
            const email = targetRow.querySelector('.email').textContent.trim();
            const phone = targetRow.cells[2].textContent.trim();
            const location = targetRow.cells[3].textContent.trim();

            // Populate the modal fields
            document.getElementById('editLandlordId').value = id;
            document.getElementById('editLandlordName').value = name;
            document.getElementById('editLandlordEmail').value = email;
            document.getElementById('editLandlordPhone').value = phone;
            document.getElementById('editLandlordLocation').value = location;

            // Show the modal
            document.getElementById('editLandlordModal').classList.add('active');
        }

        function viewLandlordRooms(id) {
            // Switch to rooms tab and filter by landlord
            document.querySelector('[data-tab="rooms"]').click();
            document.getElementById('landlord-select').value = id;
            loadLandlordRooms();
        }


        // Loader functions
        function showLoader() {
            document.getElementById('loader').style.display = "flex";;
        }

        function hideLoader() {
            document.getElementById('loader').style.display = "none";;
        }

        // Hide loader when page is fully loaded
        window.addEventListener("load", function() {
            console.log("Page loaded");
            hideLoader();
        });

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
    function showFailMessage(message) {
        // Create a temporary success message
        const successMsg = document.createElement('div');
        successMsg.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #a72828ff;
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
    function submitEditLandlordForm() {
        const formData = $('#editLandlordForm').serialize();
        showLoader();

        $.ajax({
            url: "../api/RegisterLandlord.php", // Endpoint to handle updates
            type: "POST",
            data: formData,
            success: function(response){
                hideLoader();
                const res = typeof response === "string" ? JSON.parse(response) : response;

                if (res.error) {
                    showFailMessage("Error: " + res.error);
                } else {
                    closeModal('editLandlordModal');
                    showSuccessMessage("Landlord updated successfully");
                    // Optionally refresh the page or update the table
                    setTimeout(() => {
                        location.reload(); // Or call a function to refresh the table dynamically
                    }, 1500);
                }
            },
            error: function(xhr){
                hideLoader();
                showFailMessage("Error updating landlord: refresh and try again.");
            }
        });
    }

    </script>
</body>
</html>