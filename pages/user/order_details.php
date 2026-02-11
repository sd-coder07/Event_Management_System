<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();
$userId = getCurrentUserId();
$orderId = intval($_GET['order_id'] ?? 0);

// Verify order belongs to user
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->bind_param("ii", $orderId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirectWithMessage('order_status.php', 'Order not found', 'error');
}

$order = $result->fetch_assoc();
$stmt->close();

// Get order items
$orderItems = $conn->query("SELECT oi.*, p.product_name, p.product_image, v.name as vendor_name 
                            FROM order_items oi 
                            JOIN products p ON oi.product_id = p.product_id 
                            JOIN vendors v ON oi.vendor_id = v.vendor_id 
                            WHERE oi.order_id = $orderId");

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="order_status.php" class="btn btn-light">Back</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">Order Details</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <!-- Order Status Timeline -->
        <div class="card">
            <div class="card-header">Order Status - <?= htmlspecialchars($order['order_number']) ?></div>
            
            <div style="display: flex; justify-content: space-around; padding: 40px; background: #f8f9fa; border-radius: 10px; margin: 20px 0;">
                <?php
                $statuses = ['Received', 'Ready for Shipping', 'Out For Delivery', 'Delivered'];
                $currentStatus = $order['order_status'];
                $currentIndex = array_search($currentStatus, $statuses);
                
                foreach ($statuses as $index => $status):
                    $isActive = $index <= $currentIndex;
                ?>
                <div style="text-align: center; flex: 1;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background-color: <?= $isActive ? '#5cb85c' : '#ddd' ?>; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 24px;">
                        <?= $isActive ? '✓' : ($index + 1) ?>
                    </div>
                    <p style="font-weight: bold; color: <?= $isActive ? '#5cb85c' : '#999' ?>;">
                        <?= htmlspecialchars($status) ?>
                    </p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Order Information -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div class="card">
                <div class="card-header">Delivery Information</div>
                <table>
                    <tr>
                        <th>Name:</th>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?= htmlspecialchars($order['phone']) ?></td>
                    </tr>
                    <tr>
                        <th>Address:</th>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                    </tr>
                    <tr>
                        <th>City:</th>
                        <td><?= htmlspecialchars($order['city']) ?></td>
                    </tr>
                    <tr>
                        <th>State:</th>
                        <td><?= htmlspecialchars($order['state']) ?></td>
                    </tr>
                    <tr>
                        <th>Pincode:</th>
                        <td><?= htmlspecialchars($order['pincode']) ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="card">
                <div class="card-header">Order Summary</div>
                <table>
                    <tr>
                        <th>Order Number:</th>
                        <td><?= htmlspecialchars($order['order_number']) ?></td>
                    </tr>
                    <tr>
                        <th>Order Date:</th>
                        <td><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method:</th>
                        <td><?= htmlspecialchars($order['payment_method']) ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount:</th>
                        <td style="font-size: 20px; color: #4a7fc1; font-weight: bold;">
                            <?= formatCurrency($order['total_amount']) ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Order Items -->
        <div class="card">
            <div class="card-header">Ordered Items</div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Vendor</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $orderItems->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php if ($item['product_image']): ?>
                                <img src="../../uploads/<?= $item['product_image'] ?>" alt="Product" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #ddd; border-radius: 5px;"></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['vendor_name']) ?></td>
                            <td><?= formatCurrency($item['price']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= formatCurrency($item['price'] * $item['quantity']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="dashboard.php" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
</body>
</html>
