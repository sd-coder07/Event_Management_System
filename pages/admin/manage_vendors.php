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
            $category = sanitizeInput($_POST['category']);
            
            $stmt = $conn->prepare("INSERT INTO vendors (name, email, password, category) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $password, $category);
            
            if ($stmt->execute()) {
                redirectWithMessage('manage_vendors.php', 'Vendor added successfully', 'success');
            }
            $stmt->close();
        } elseif ($_POST['action'] === 'delete') {
            $vendorId = intval($_POST['vendor_id']);
            $stmt = $conn->prepare("DELETE FROM vendors WHERE vendor_id = ?");
            $stmt->bind_param("i", $vendorId);
            $stmt->execute();
            $stmt->close();
            redirectWithMessage('manage_vendors.php', 'Vendor deleted successfully', 'success');
        } elseif ($_POST['action'] === 'toggle_status') {
            $vendorId = intval($_POST['vendor_id']);
            $newStatus = sanitizeInput($_POST['new_status']);
            
            $stmt = $conn->prepare("UPDATE vendors SET status = ? WHERE vendor_id = ?");
            $stmt->bind_param("si", $newStatus, $vendorId);
            $stmt->execute();
            $stmt->close();
            redirectWithMessage('manage_vendors.php', 'Vendor status updated', 'success');
        }
    }
}

// Fetch all vendors
$vendors = $conn->query("SELECT * FROM vendors ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vendors - Event Management System</title>
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
                    <div class="header-title">Vendor Management</div>
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
        
        <!-- Add Vendor Form -->
        <div class="card">
            <div class="card-header">Add New Vendor</div>
            <form action="" method="POST">
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
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Catering">Catering</option>
                            <option value="Florist">Florist</option>
                            <option value="Decoration">Decoration</option>
                            <option value="Lighting">Lighting</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Add Vendor</button>
            </form>
        </div>
        
        <!-- Vendors List -->
        <div class="card">
            <div class="card-header">All Vendors</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($vendor = $vendors->fetch_assoc()): ?>
                        <tr>
                            <td><?= $vendor['vendor_id'] ?></td>
                            <td><?= htmlspecialchars($vendor['name']) ?></td>
                            <td><?= htmlspecialchars($vendor['email']) ?></td>
                            <td><?= htmlspecialchars($vendor['category']) ?></td>
                            <td>
                                <span style="padding: 5px 10px; border-radius: 5px; background-color: <?= $vendor['status'] === 'active' ? '#5cb85c' : '#d9534f' ?>; color: white;">
                                    <?= ucfirst($vendor['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($vendor['created_at'])) ?></td>
                            <td>
                                <form action="" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="vendor_id" value="<?= $vendor['vendor_id'] ?>">
                                    <input type="hidden" name="new_status" value="<?= $vendor['status'] === 'active' ? 'inactive' : 'active' ?>">
                                    <button type="submit" class="btn btn-secondary" style="padding: 5px 15px;">
                                        <?= $vendor['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>
                                <form action="" method="POST" style="display: inline-block;" onsubmit="return confirmDelete('this vendor')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="vendor_id" value="<?= $vendor['vendor_id'] ?>">
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
