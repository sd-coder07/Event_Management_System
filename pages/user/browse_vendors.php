<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();

$category = sanitizeInput($_GET['category'] ?? '');
$selectedVendor = intval($_GET['vendor_id'] ?? 0);

// Get vendors by category
if ($category) {
    $stmt = $conn->prepare("SELECT * FROM vendors WHERE category = ? AND status = 'active'");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $vendors = $stmt->get_result();
    $stmt->close();
} else {
    $vendors = $conn->query("SELECT * FROM vendors WHERE status = 'active'");
}

// Get products if vendor selected
$products = null;
$vendorName = '';
if ($selectedVendor) {
    $stmt = $conn->prepare("SELECT v.name, v.category FROM vendors WHERE vendor_id = ?");
    $stmt->bind_param("i", $selectedVendor);
    $stmt->execute();
    $vendorInfo = $stmt->get_result()->fetch_assoc();
    $vendorName = $vendorInfo['name'];
    $stmt->close();
    
    $stmt = $conn->prepare("SELECT * FROM products WHERE vendor_id = ? AND status = 'available'");
    $stmt->bind_param("i", $selectedVendor);
    $stmt->execute();
    $products = $stmt->get_result();
    $stmt->close();
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = intval($_POST['product_id']);
    $vendorId = intval($_POST['vendor_id']);
    $userId = getCurrentUserId();
    
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, vendor_id, quantity) VALUES (?, ?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1");
    $stmt->bind_param("iii", $userId, $productId, $vendorId);
    $stmt->execute();
    $stmt->close();
    
    redirectWithMessage("browse_vendors.php?category=$category&vendor_id=$selectedVendor", 'Product added to cart!', 'success');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Vendors - Event Management System</title>
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
                    <div class="header-title"><?= $category ? htmlspecialchars($category) : 'All' ?> Vendors</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <?php if (!$selectedVendor): ?>
        <!-- Vendors List -->
        <div class="card">
            <div class="card-header">Select a Vendor</div>
            <div class="product-grid">
                <?php while ($vendor = $vendors->fetch_assoc()): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($vendor['name']) ?></h3>
                    <p style="color: #666; margin: 10px 0;"><?= htmlspecialchars($vendor['category']) ?></p>
                    <p style="color: #888; font-size: 14px;">Contact Details</p>
                    <a href="?category=<?= urlencode($category) ?>&vendor_id=<?= $vendor['vendor_id'] ?>" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Shop Item</a>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php else: ?>
        <!-- Vendor Products -->
        <div style="margin-bottom: 20px;">
            <a href="browse_vendors.php?category=<?= urlencode($category) ?>" class="btn btn-secondary">Back to Vendors</a>
        </div>
        
        <div class="card">
            <div class="card-header"><?= htmlspecialchars($vendorName) ?> - Products</div>
            <div style="text-align: center; margin-bottom: 20px;">
                <a href="cart.php" class="btn btn-success">View Cart</a>
            </div>
            
            <div class="product-grid">
                <?php if ($products && $products->num_rows > 0): ?>
                    <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="product-card">
                        <?php if ($product['product_image']): ?>
                        <img src="../../uploads/<?= $product['product_image'] ?>" alt="Product" class="product-image">
                        <?php else: ?>
                        <div style="width: 100%; height: 200px; background: #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">No Image</div>
                        <?php endif; ?>
                        <div class="product-name"><?= htmlspecialchars($product['product_name']) ?></div>
                        <div class="product-price"><?= formatCurrency($product['product_price']) ?></div>
                        <form action="" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <input type="hidden" name="vendor_id" value="<?= $selectedVendor ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary" style="width: 100%;">Add to Cart</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                <p style="text-align: center; padding: 40px; color: #999;">No products available from this vendor</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>
