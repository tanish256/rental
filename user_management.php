<?php
require 'auth-check2.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        switch ($action) {
            case 'add_user':
                addUser($pdo);
                break;
            case 'edit_user':
                editUser($pdo);
                break;
            case 'delete_user':
                deleteUser($pdo);
                break;
        }
    }
}

// Add new user
function addUser($pdo) {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    $sql = "INSERT INTO users (user_name, email, name, pass, role, status, date_added) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$user_name, $email, $name, $pass, $role, $status])) {
        echo json_encode(['success' => true, 'message' => 'User added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding user']);
    }
    exit;
}

// Edit user
function editUser($pdo) {
    $id = $_POST['id'];
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Check if password is being updated
    if (!empty($_POST['password'])) {
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET user_name = ?, email = ?, name = ?, pass = ?, role = ?, status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$user_name, $email, $name, $pass, $role, $status, $id]);
    } else {
        $sql = "UPDATE users SET user_name = ?, email = ?, name = ?, role = ?, status = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$user_name, $email, $name, $role, $status, $id]);
    }
    
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating user']);
    }
    exit;
}

// Delete user
function deleteUser($pdo) {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting user']);
    }
    exit;
}

// Get user statistics
function getUserStats($pdo) {
    $stats = [];
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $stats['total'] = $stmt->fetch()['total'];
    
    // Admin users
    $stmt = $pdo->query("SELECT COUNT(*) as admins FROM users WHERE role = 'admin'");
    $stats['admins'] = $stmt->fetch()['admins'];
    
    // Manager users
    $stmt = $pdo->query("SELECT COUNT(*) as superadmins FROM users WHERE role = 'super-admin'");
    $stats['superadmins'] = $stmt->fetch()['superadmins'];
    
    return $stats;
}

// Get all users
function getAllUsers($pdo, $search = '', $sort = 'name') {
    $sql = "SELECT id, user_name, email, name, role, status, DATE_FORMAT(date_added, '%Y-%m-%d') as date_added FROM users";
    
    $params = [];
    
    if (!empty($search)) {
        $sql .= " WHERE name LIKE ? OR email LIKE ? OR user_name LIKE ?";
        $searchTerm = "%$search%";
        $params = [$searchTerm, $searchTerm, $searchTerm];
    }
    
    // Add sorting
    switch ($sort) {
        case 'name':
            $sql .= " ORDER BY name";
            break;
        case 'role':
            $sql .= " ORDER BY role";
            break;
        case 'date':
            $sql .= " ORDER BY date_added DESC";
            break;
        case 'status':
            $sql .= " ORDER BY status";
            break;
        default:
            $sql .= " ORDER BY name";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll();
}

// Get user by ID
function getUserById($pdo, $id) {
    $sql = "SELECT id, user_name, email, name, role, status FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    return $stmt->fetch();
}

// Get user statistics and users
$userStats = getUserStats($pdo);
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'name';
$users = getAllUsers($pdo, $search, $sort);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Management - Rental System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/admin.css">
</head>

<body>
    <!-- Loader HTML -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <div class="root">
        <?php include 'sidebar.php'; ?>
        <div class="dashmain">
            <div class="headers">
                <h1>User Management</h1>
                <p>Manage system users and their permissions</p>
            </div>

            <!-- User Summary Cards -->
            <div class="summary">
                <div class="sum">
                    <div class="circle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M20.59 22C20.59 18.13 16.74 15 12 15C7.26 15 3.41 18.13 3.41 22" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="inf">
                        <h3>Total Users</h3>
                        <h4><?php echo $userStats['total']; ?></h4>
                        <p>All system users</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 15C15.3137 15 18 12.3137 18 9C18 5.68629 15.3137 3 12 3C8.68629 3 6 5.68629 6 9C6 12.3137 8.68629 15 12 15Z" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.90527 20.2491C3.82736 18.6531 5.15322 17.3278 6.74966 16.4064C8.3461 15.485 10.1569 15 12.0002 15C13.8434 15 15.6542 15.4851 17.2506 16.4065C18.8471 17.3279 20.173 18.6533 21.0951 20.2493" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="inf">
                        <h3>Admins</h3>
                        <h4><?php echo $userStats['admins']; ?></h4>
                        <p>Administrative users</p>
                    </div>
                </div>

                <div class="sum">
                    <div class="circle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" 
                                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="inf">
                        <h3>Super Admins</h3>
                        <h4><?php echo $userStats['superadmins']; ?></h4>
                        <p>Super Admins</p>
                    </div>
                </div>
            </div>

            <!-- User Management Table -->
            <div class="tablecard">
                <div class="tops">
                    <div class="left">
                        <h2>All Users</h2>
                    </div>
                    <div class="right">
                        <div class="sort-component">
                            <span class="sort-label">Sort by:</span>
                            <select class="sort-select" id="userSort">
                                <option value="name" <?php echo $sort === 'name' ? 'selected' : ''; ?>>Name</option>
                                <option value="role" <?php echo $sort === 'role' ? 'selected' : ''; ?>>Role</option>
                                <option value="date" <?php echo $sort === 'date' ? 'selected' : ''; ?>>Date Added</option>
                                <option value="status" <?php echo $sort === 'status' ? 'selected' : ''; ?>>Status</option>
                            </select>
                        </div>
                        <form method="GET" style="display: inline;">
                            <input type="text" id="searchUsers" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
                            <input type="hidden" name="sort" id="sortInput" value="<?php echo $sort; ?>">
                        </form>
                        <button class="addbtn" id="addUserBtn">Add User</button>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <?php
                                // Status badge class
                                $statusClass = '';
                                $statusText = '';
                                switch($user['status']) {
                                    case 'active':
                                        $statusClass = 'status-active';
                                        $statusText = 'Active';
                                        break;
                                    case 'suspended':
                                        $statusClass = 'status-edit';
                                        $statusText = 'Suspended';
                                        break;
                                    default:
                                        $statusClass = 'status-active';
                                        $statusText = 'Active';
                                }
                                
                                // Role badge
                                $roleBadge = '';
                                switch($user['role']) {
                                    case 'admin':
                                        $roleBadge = '<span style="background: #4ecdc4; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">Admin</span>';
                                        break;
                                    case 'super-admin':
                                        $roleBadge = '<span style="background: #ff6b6b; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">Super Admin</span>';
                                        break;
                                    case 'user':
                                        $roleBadge = '<span style="background: #45b7d1; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">User</span>';
                                        break;
                                    default:
                                        $roleBadge = '<span style="background: #45b7d1; color: white; padding: 4px 8px; border-radius: 12px; font-size: 0.75rem;">User</span>';
                                }
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo $roleBadge; ?></td>
                                    <td><?php echo date('M j, Y', strtotime($user['date_added'])); ?></td>
                                    <td class="<?php echo $statusClass; ?>"><div><?php echo $statusText; ?></div></td>
                                    <td>
                                        <button class="edit-user" data-id="<?php echo $user['id']; ?>">Edit</button>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button class="delete-user" data-id="<?php echo $user['id']; ?>">Delete</button>
                                        <?php else: ?>
                                            <button class="delete-user" onclick="alert('Cannot delete your own account')">Delete</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 20px;">
                                    No users found matching your criteria.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit User Modal -->
    <div class="Tparent" id="userModal">
        <div class="card">
            <button class="x" id="closeModal">&times;</button>
            <div class="header">
                <h2 id="modalTitle">Add New User</h2>
                <p class="subtitle" id="modalSubtitle">Create a new user account</p>
            </div>
            <div class="content">
                <form id="userForm">
                    <input type="hidden" id="userId" name="id">
                    <input type="hidden" name="action" id="formAction" value="add_user">
                    
                    <div class="form-group">
                        <label for="userName">
                            <span class="icon">👤</span> Full Name
                        </label>
                        <input type="text" id="userName" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="userUserName">
                            <span class="icon">👤</span> Username
                        </label>
                        <input type="text" id="userUserName" name="user_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="userEmail">
                            <span class="icon">✉️</span> Email Address
                        </label>
                        <input type="email" id="userEmail" name="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="userRole">
                            <span class="icon">🔑</span> User Role
                        </label>
                        <select id="userRole" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Administrator</option>
                            <option value="super-admin">Super Admin</option>
                            <option value="user">Standard User</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="userPassword">
                            <span class="icon">🔒</span> Password
                        </label>
                        <input type="password" id="userPassword" name="password">
                        <small style="color: #666; font-size: 0.8rem;">Leave blank to keep current password (when editing)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmPassword">
                            <span class="icon">🔒</span> Confirm Password
                        </label>
                        <input type="password" id="confirmPassword" name="confirm_password">
                    </div>
                    
                    <div class="form-group">
                        <label for="userStatus">
                            <span class="icon">📊</span> Status
                        </label>
                        <select id="userStatus" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="footer">
                <button class="btn btn-outline" id="cancelBtn">Cancel</button>
                <button class="btn btn-primary" id="saveUserBtn">
                    <span class="icon">💾</span> Save User
                </button>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script>
        // DOM elements
        const userModal = document.getElementById('userModal');
        const userForm = document.getElementById('userForm');
        const modalTitle = document.getElementById('modalTitle');
        const modalSubtitle = document.getElementById('modalSubtitle');
        const addUserBtn = document.getElementById('addUserBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveUserBtn = document.getElementById('saveUserBtn');
        const searchUsers = document.getElementById('searchUsers');
        const userSort = document.getElementById('userSort');
        const sortInput = document.getElementById('sortInput');

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Modal controls
            addUserBtn.addEventListener('click', openAddUserModal);
            closeModal.addEventListener('click', closeUserModal);
            cancelBtn.addEventListener('click', closeUserModal);
            saveUserBtn.addEventListener('click', saveUser);
            
            // Search and sort
            searchUsers.addEventListener('input', function() {
                this.form.submit();
            });
            
            userSort.addEventListener('change', function() {
                sortInput.value = this.value;
                document.querySelector('form').submit();
            });
            
            // Close modal when clicking outside
            userModal.addEventListener('click', function(e) {
                if (e.target === userModal) {
                    closeUserModal();
                }
            });
            
            // Edit user buttons
            document.querySelectorAll('.edit-user').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = parseInt(this.getAttribute('data-id'));
                    editUser(userId);
                });
            });
            
            // Delete user buttons
            document.querySelectorAll('.delete-user:not([disabled])').forEach(button => {
                button.addEventListener('click', function() {
                    if(!this.getAttribute('data-id')) return;
                    const userId = parseInt(this.getAttribute('data-id'));
                    deleteUser(userId);
                });
            });
        }

        // Open modal for adding a new user
        function openAddUserModal() {
            modalTitle.textContent = 'Add New User';
            modalSubtitle.textContent = 'Create a new user account';
            userForm.reset();
            document.getElementById('userId').value = '';
            document.getElementById('formAction').value = 'add_user';
            // Make password required for new users
            document.getElementById('userPassword').required = true;
            document.getElementById('confirmPassword').required = true;
            userModal.classList.add('active');
        }

        // Open modal for editing an existing user
        function editUser(userId) {
            // Fetch user data via AJAX
            fetch(`get_user.php?id=${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(user => {
                    if (user.error) {
                        throw new Error(user.error);
                    }
                    
                    modalTitle.textContent = 'Edit User';
                    modalSubtitle.textContent = `Edit ${user.name}'s account details`;
                    
                    document.getElementById('userId').value = user.id;
                    document.getElementById('userName').value = user.name;
                    document.getElementById('userUserName').value = user.user_name;
                    document.getElementById('userEmail').value = user.email || '';
                    document.getElementById('userRole').value = user.role;
                    document.getElementById('userStatus').value = user.status;
                    document.getElementById('formAction').value = 'edit_user';
                    
                    // Make password optional for editing
                    document.getElementById('userPassword').required = false;
                    document.getElementById('confirmPassword').required = false;
                    
                    // Clear password fields when editing
                    document.getElementById('userPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                    
                    userModal.classList.add('active');
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                    alert('Error loading user data: ' + error.message);
                });
        }

        // Close the user modal
        function closeUserModal() {
            userModal.classList.remove('active');
        }

        // Save user (add or update)
        function saveUser() {
            const formData = new FormData(userForm);
            
            // Basic validation
            const name = document.getElementById('userName').value;
            const userUserName = document.getElementById('userUserName').value;
            const role = document.getElementById('userRole').value;
            const status = document.getElementById('userStatus').value;
            const password = document.getElementById('userPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const isEdit = document.getElementById('formAction').value === 'edit_user';
            
            if (!name || !userUserName || !role || !status) {
                alert('Please fill in all required fields.');
                return;
            }
            
            // Password validation
            if (!isEdit && !password) {
                alert('Password is required for new users.');
                return;
            }
            
            if (password && password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }
            
            // Show loading state
            saveUserBtn.disabled = true;
            saveUserBtn.innerHTML = '<span class="icon">⏳</span> Saving...';
            
            // Submit form via AJAX
            fetch('user_management.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeUserModal();
                    location.reload(); // Reload to show updated data
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving user: ' + error.message);
            })
            .finally(() => {
                // Reset button state
                saveUserBtn.disabled = false;
                saveUserBtn.innerHTML = '<span class="icon">💾</span> Save User';
            });
        }

        // Delete user with confirmation
        function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('action', 'delete_user');
            formData.append('id', userId);
            
            fetch('user_management.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Reload to show updated data
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting user: ' + error.message);
            });
        }

        // Hide loader after page is fully loaded
        window.addEventListener("load", function() {
            document.getElementById("loader").style.display = "none";
        });
    </script>
</body>

</html>