<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();
$userId = getCurrentUserId();

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cartId = intval($_POST['cart_id']);
        $quantity = intval($_POST['quantity']);
        
        if ($quantity > 0) {
            $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ? AND user_id = ?");
            $stmt->bind_param("iii", $quantity, $cartId, $userId);
            $stmt->execute();
            $stmt->close();
        }
        redirectWithMessage('cart.php', 'Cart updated', 'success');
    } elseif (isset($_POST['remove_item'])) {
        $cartId = intval($_POST['cart_id']);
        
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cartId, $userId);
        $stmt->execute();
        $stmt->close();
        redirectWithMessage('cart.php', 'Item removed from cart', 'success');
    } elseif (isset($_POST['clear_cart'])) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->close();
        redirectWithMessage('cart.php', 'Cart cleared', 'success');
    }
}

// Get cart items
$query = "SELECT c.*, p.product_name, p.product_price, p.product_image, v.name as vendor_name 
          FROM cart c 
          JOIN products p ON c.product_id = p.product_id 
          JOIN vendors v ON c.vendor_id = v.vendor_id 
          WHERE c.user_id = $userId";
$cartItems = $conn->query($query);

// Calculate total
$total = 0;
$tempConn = getDBConnection();
$tempItems = $tempConn->query($query);
while ($item = $tempItems->fetch_assoc()) {
    $total += $item['product_price'] * $item['quantity'];
}
closeDBConnection($tempConn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Event Management System</title>
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
                    <div class="header-title">Your Shopping Cart</div>
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
            <div class="card-header">Cart Items</div>
            
            <?php if ($cartItems->num_rows > 0): ?>
                <?php while ($item = $cartItems->fetch_assoc()): ?>
                <div class="cart-item">
                    <div>
                        <?php if ($item['product_image']): ?>
                        <img src="../../uploads/<?= $item['product_image'] ?>" alt="Product" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                        <?php else: ?>
                        <div style="width: 80px; height: 80px; background: #ddd; border-radius: 5px;"></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($item['product_name']) ?></strong>
                        <p style="color: #666; font-size: 14px;">Vendor: <?= htmlspecialchars($item['vendor_name']) ?></p>
                    </div>
                    <div><?= formatCurrency($item['product_price']) ?></div>
                    <div>
                        <form action="" method="POST" style="display: inline-flex; align-items: center; gap: 5px;">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width: 60px; padding: 5px;">
                            <button type="submit" name="update_quantity" class="btn btn-secondary" style="padding: 5px 10px;">Update</button>
                        </form>
                    </div>
                    <div><?= formatCurrency($item['product_price'] * $item['quantity']) ?></div>
                    <div>
                        <form action="" method="POST" onsubmit="return confirmDelete('this item from cart')">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <button type="submit" name="remove_item" class="btn btn-danger" style="padding: 5px 15px;">Remove</button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
                
                <!-- Cart Summary -->
                <div class="cart-summary" style="margin-top: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Grand Total</h2>
                        <h2 style="color: #4a7fc1;"><?= formatCurrency($total) ?></h2>
                    </div>
                    
                    <div style="display: flex; gap: 10px; justify-content: space-between;">
                        <form action="" method="POST" onsubmit="return confirmAction('clear your cart')">
                            <button type="submit" name="clear_cart" class="btn btn-danger">Delete All</button>
                        </form>
                        <a href="checkout.php" class="btn btn-success" style="padding: 15px 40px; font-size: 18px;">Proceed to CheckOut</a>
                    </div>
                </div>
            <?php else: ?>
            <p style="text-align: center; padding: 40px; color: #999;">
                Your cart is empty. <a href="browse_vendors.php">Start shopping</a>
            </p>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
<?php closeDBConnection($conn); ?>
