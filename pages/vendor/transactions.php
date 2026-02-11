<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

$conn = getDBConnection();
$vendorId = getCurrentUserId();

// Get transactions (orders)
$query = "SELECT o.*, u.name as user_name 
          FROM orders o 
          JOIN order_items oi ON o.order_id = oi.order_id 
          JOIN users u ON o.user_id = u.user_id
          WHERE oi.vendor_id = $vendorId 
          GROUP BY o.order_id 
          ORDER BY o.created_at DESC";
$transactions = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions - Event Management System</title>
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
                    <div class="header-title">Vendor Transactions</div>
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
            <div class="card-header">All Transactions</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($transaction = $transactions->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($transaction['order_number']) ?></td>
                            <td><?= htmlspecialchars($transaction['user_name']) ?></td>
                            <td><?= formatCurrency($transaction['total_amount']) ?></td>
                            <td><?= htmlspecialchars($transaction['order_status']) ?></td>
                            <td><?= date('M d, Y', strtotime($transaction['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>
