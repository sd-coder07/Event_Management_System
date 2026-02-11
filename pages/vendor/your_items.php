<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

$conn = getDBConnection();
$vendorId = getCurrentUserId();
$products = $conn->query("SELECT * FROM products WHERE vendor_id = $vendorId ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Items - Event Management System</title>
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
                    <div class="header-title">Your Items</div>
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
            <div class="card-header">Your Products</div>
            <div class="product-grid">
                <?php while ($product = $products->fetch_assoc()): ?>
                <div class="product-card">
                    <?php if ($product['product_image']): ?>
                    <img src="../../uploads/<?= $product['product_image'] ?>" alt="Product" class="product-image">
                    <?php else: ?>
                    <div style="width: 100%; height: 200px; background: #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">No Image</div>
                    <?php endif; ?>
                    <div class="product-name"><?= htmlspecialchars($product['product_name']) ?></div>
                    <div class="product-price"><?= formatCurrency($product['product_price']) ?></div>
                    <div class="action-buttons">
                        <form action="delete_product.php" method="POST" onsubmit="return confirmDelete('this product')">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <button type="submit" class="btn btn-danger" style="width: 100%;">Delete</button>
                        </form>
                        <form action="edit_product.php" method="GET">
                            <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Update</button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <?php if ($products->num_rows === 0): ?>
            <p style="text-align: center; padding: 40px; color: #999;">No products added yet. <a href="add_item.php">Add your first product</a></p>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
<?php closeDBConnection($conn); ?>
