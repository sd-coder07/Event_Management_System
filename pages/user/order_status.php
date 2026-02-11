<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();
$userId = getCurrentUserId();

// Get all user orders
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status - Event Management System</title>
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
                    <div class="header-title">User Order Status</div>
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
            <div class="card-header">Your Orders</div>
            
            <?php if ($orders->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Name</th>
                            <th>E-mail</th>
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
                            <td><?= htmlspecialchars($order['name']) ?></td>
                            <td><?= htmlspecialchars($order['email']) ?></td>
                            <td><?= htmlspecialchars($order['address']) . ', ' . $order['city'] . ', ' . $order['state'] . ' - ' . $order['pincode'] ?></td>
                            <td><?= formatCurrency($order['total_amount']) ?></td>
                            <td>
                                <span style="padding: 5px 10px; border-radius: 5px; background-color: 
                                    <?php 
                                    switch($order['order_status']) {
                                        case 'Received': echo '#6c757d'; break;
                                        case 'Ready for Shipping': echo '#ffc107'; break;
                                        case 'Out For Delivery': echo '#17a2b8'; break;
                                        case 'Delivered': echo '#5cb85c'; break;
                                    }
                                    ?>; color: white;">
                                    <?= htmlspecialchars($order['order_status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-primary" style="padding: 5px 15px;">Check Status</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #999;">
                No orders yet. <a href="browse_vendors.php">Start shopping</a>
            </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>
