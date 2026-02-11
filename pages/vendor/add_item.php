<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = sanitizeInput($_POST['product_name']);
    $productPrice = floatval($_POST['product_price']);
    $vendorId = getCurrentUserId();
    $category = $_SESSION['vendor_category'];
    
    // Handle image upload
    $productImage = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $productImage = uploadImage($_FILES['product_image']);
        if (!$productImage) {
            redirectWithMessage('add_item.php', 'Failed to upload image', 'error');
        }
    }
    
    $stmt = $conn->prepare("INSERT INTO products (vendor_id, product_name, product_price, product_image, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $vendorId, $productName, $productPrice, $productImage, $category);
    
    if ($stmt->execute()) {
        redirectWithMessage('your_items.php', 'Product added successfully!', 'success');
    } else {
        redirectWithMessage('add_item.php', 'Failed to add product', 'error');
    }
    $stmt->close();
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header style="background: linear-gradient(135deg, #4a7fc1, #5c8fd6);">
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="dashboard.php" class="btn btn-light">Home</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">Welcome 'Vendor Name'</div>
                </div>
                <div class="nav-right" style="display: flex; gap: 10px;">
                    <a href="product_status.php" class="btn btn-outline">Product Status</a>
                    <a href="request_items.php" class="btn btn-outline">Request Item</a>
                    <a href="your_items.php" class="btn btn-outline">View Product</a>
                    <a href="../auth/logout.php" class="btn btn-light">Log Out</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-top: 30px;">
            <!-- Add Product Form -->
            <div class="card">
                <div class="card-header">Add New Product</div>
                <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateProductForm(this)">
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Product Price</label>
                        <input type="number" name="product_price" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="product_image" class="form-control" accept="image/*">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Add The Product</button>
                </form>
            </div>
            
            <!-- Products List -->
            <div class="card">
                <div class="card-header">Your Products</div>
                <?php
                $conn = getDBConnection();
                $vendorId = getCurrentUserId();
                $products = $conn->query("SELECT * FROM products WHERE vendor_id = $vendorId ORDER BY created_at DESC");
                ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Product Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if ($product['product_image']): ?>
                                    <img src="../../uploads/<?= $product['product_image'] ?>" alt="Product" style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                    <div style="width: 80px; height: 80px; background: #ddd; border-radius: 5px; display: flex; align-items: center; justify-content: center;">No Image</div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['product_name']) ?></td>
                                <td><?= formatCurrency($product['product_price']) ?></td>
                                <td>
                                    <form action="delete_product.php" method="POST" style="display: inline-block;" onsubmit="return confirmDelete('this product')">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" class="btn btn-danger" style="padding: 5px 15px;">Delete</button>
                                    </form>
                                    <form action="edit_product.php" method="GET" style="display: inline-block;">
                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                        <button type="submit" class="btn btn-primary" style="padding: 5px 15px;">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php closeDBConnection($conn); ?>
            </div>
        </div>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
