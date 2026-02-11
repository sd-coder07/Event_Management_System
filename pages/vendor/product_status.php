<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

$conn = getDBConnection();
$vendorId = getCurrentUserId();

// Get all orders containing this vendor's products
$query = "SELECT DISTINCT o.*, u.name as user_name, u.email as user_email 
          FROM orders o 
          JOIN order_items oi ON o.order_id = oi.order_id 
          JOIN users u ON o.user_id = u.user_id
          WHERE oi.vendor_id = $vendorId 
          ORDER BY o.created_at DESC";
$orders = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Status - Event Management System</title>
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
                    <div class="header-title">Product Status</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <div class="card">
            <div class="card-header">Your Product Orders</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_number']) ?></td>
                            <td><?= htmlspecialchars($order['user_name']) ?></td>
                            <td><?= htmlspecialchars($order['user_email']) ?></td>
                            <td><?= htmlspecialchars($order['address']) . ', ' . $order['city'] ?></td>
                            <td><?= formatCurrency($order['total_amount']) ?></td>
                            <td>
                                <span style="padding: 5px 10px; border-radius: 5px; background-color: #4a7fc1; color: white;">
                                    <?= htmlspecialchars($order['order_status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="view_order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-primary" style="padding: 5px 15px;">View Details</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($orders->num_rows === 0): ?>
            <p style="text-align: center; padding: 40px; color: #999;">No orders yet</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>
