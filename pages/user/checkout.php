<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();
$userId = getCurrentUserId();

// Get cart items for display
$query = "SELECT c.*, p.product_name, p.product_price, p.product_image 
          FROM cart c 
          JOIN products p ON c.product_id = p.product_id 
          WHERE c.user_id = $userId";
$cartItems = $conn->query($query);

// Check if cart is empty
if ($cartItems->num_rows === 0) {
    redirectWithMessage('cart.php', 'Your cart is empty', 'error');
}

// Calculate total
$total = 0;
$tempConn = getDBConnection();
$tempItems = $tempConn->query($query);
while ($item = $tempItems->fetch_assoc()) {
    $total += $item['product_price'] * $item['quantity'];
}
closeDBConnection($tempConn);

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $city = sanitizeInput($_POST['city']);
    $state = sanitizeInput($_POST['state']);
    $pincode = sanitizeInput($_POST['pincode']);
    $paymentMethod = sanitizeInput($_POST['payment_method']);
    
    // Generate order number
    $orderNumber = generateOrderNumber();
    
    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, total_amount, payment_method, name, email, phone, address, city, state, pincode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdssssssss", $userId, $orderNumber, $total, $paymentMethod, $name, $email, $phone, $address, $city, $state, $pincode);
    
    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;
        $stmt->close();
        
        // Insert order items
        $cartItemsForOrder = $conn->query($query);
        while ($item = $cartItemsForOrder->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, vendor_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiid", $orderId, $item['product_id'], $item['vendor_id'], $item['quantity'], $item['product_price']);
            $stmt->execute();
            $stmt->close();
        }
        
        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to success page
        $_SESSION['order_number'] = $orderNumber;
        header("Location: order_success.php");
        exit();
    } else {
        redirectWithMessage('checkout.php', 'Failed to place order. Please try again.', 'error');
    }
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="cart.php" class="btn btn-light">Back to Cart</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">Checkout</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">Order Summary</div>
                <?php
                $conn2 = getDBConnection();
                $cartItems2 = $conn2->query($query);
                ?>
                <?php while ($item = $cartItems2->fetch_assoc()): ?>
                <div style="display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #eee;">
                    <div>
                        <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                        <p style="color: #666; font-size: 14px;">Qty: <?= $item['quantity'] ?></p>
                    </div>
                    <div>
                        <?= formatCurrency($item['product_price'] * $item['quantity']) ?>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php closeDBConnection($conn2); ?>
                
                <div style="display: flex; justify-content: space-between; padding: 20px; border-top: 2px solid #4a7fc1; margin-top: 10px;">
                    <h3>Grand Total</h3>
                    <h3 style="color: #4a7fc1;"><?= formatCurrency($total) ?></h3>
                </div>
            </div>
            
            <!-- Checkout Form -->
            <div class="card">
                <div class="card-header">Delivery Details</div>
                <form action="" method="POST" onsubmit="return validateCheckoutForm(this)">
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">E-mail *</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="phone" class="form-control" maxlength="10" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Address *</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">State *</label>
                            <input type="text" name="state" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Pin Code *</label>
                            <input type="text" name="pincode" class="form-control" maxlength="6" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Payment Method *</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">Drop Down (Cash / UPI)</option>
                            <option value="Cash">Cash</option>
                            <option value="UPI">UPI</option>
                            <option value="Card">Credit/Debit Card</option>
                        </select>
                    </div>
                    
                    <button type="submit" name="place_order" class="btn btn-success" style="width: 100%; padding: 15px; font-size: 18px;">Order Now</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
