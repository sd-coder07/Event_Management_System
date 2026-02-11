<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireAdmin();

$conn = getDBConnection();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $name = sanitizeInput($_POST['name']);
            $email = sanitizeInput($_POST['email']);
            $password = hashPassword(sanitizeInput($_POST['password']));
            
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            
            if ($stmt->execute()) {
                redirectWithMessage('manage_users.php', 'User added successfully', 'success');
            } else {
                redirectWithMessage('manage_users.php', 'Failed to add user', 'error');
            }
            $stmt->close();
        } elseif ($_POST['action'] === 'delete') {
            $userId = intval($_POST['user_id']);
            $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            
            if ($stmt->execute()) {
                redirectWithMessage('manage_users.php', 'User deleted successfully', 'success');
            } else {
                redirectWithMessage('manage_users.php', 'Failed to delete user', 'error');
            }
            $stmt->close();
        } elseif ($_POST['action'] === 'toggle_status') {
            $userId = intval($_POST['user_id']);
            $newStatus = sanitizeInput($_POST['new_status']);
            
            $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
            $stmt->bind_param("si", $newStatus, $userId);
            
            if ($stmt->execute()) {
                redirectWithMessage('manage_users.php', 'User status updated', 'success');
            } else {
                redirectWithMessage('manage_users.php', 'Failed to update status', 'error');
            }
            $stmt->close();
        }
    }
}

// Fetch all users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="dashboard.php" class="btn btn-light">Home</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">User Management</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <a href="../flowchart.php" class="btn btn-secondary chart-btn">Chart</a>
        
        <?php displayMessage(); ?>
        
        <!-- Add User Form -->
        <div class="card">
            <div class="card-header">Add New User</div>
            <form action="" method="POST" onsubmit="return validateSignupForm(this)">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add User</button>
            </form>
        </div>
        
        <!-- Users List -->
        <div class="card">
            <div class="card-header">All Users</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['user_id'] ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <span style="padding: 5px 10px; border-radius: 5px; background-color: <?= $user['status'] === 'active' ? '#5cb85c' : '#d9534f' ?>; color: white;">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            <td>
                                <form action="" method="POST" style="display: inline-block;" onsubmit="return confirmAction('toggle status for this user')">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                    <input type="hidden" name="new_status" value="<?= $user['status'] === 'active' ? 'inactive' : 'active' ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 5px 15px;">
                                        <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>
                                <form action="" method="POST" style="display: inline-block;" onsubmit="return confirmDelete('this user')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 15px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
<?php closeDBConnection($conn); ?>
