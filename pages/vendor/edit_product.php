<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

$conn = getDBConnection();
$vendorId = getCurrentUserId();

// Get product details
$productId = intval($_GET['product_id'] ?? 0);
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ? AND vendor_id = ?");
$stmt->bind_param("ii", $productId, $vendorId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirectWithMessage('your_items.php', 'Product not found', 'error');
}

$product = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productName = sanitizeInput($_POST['product_name']);
    $productPrice = floatval($_POST['product_price']);
    $productImage = $product['product_image'];
    
    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $newImage = uploadImage($_FILES['product_image']);
        if ($newImage) {
            $productImage = $newImage;
        }
    }
    
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, product_price = ?, product_image = ? WHERE product_id = ? AND vendor_id = ?");
    $stmt->bind_param("sdsii", $productName, $productPrice, $productImage, $productId, $vendorId);
    
    if ($stmt->execute()) {
        redirectWithMessage('your_items.php', 'Product updated successfully!', 'success');
    } else {
        redirectWithMessage('edit_product.php?product_id=' . $productId, 'Failed to update product', 'error');
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
    <title>Edit Product - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="your_items.php" class="btn btn-light">Back</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">Edit Product</div>
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
            <div class="card-header">Edit Product Details</div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Product Price</label>
                    <input type="number" name="product_price" class="form-control" step="0.01" min="0" value="<?= $product['product_price'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Current Image</label>
                    <?php if ($product['product_image']): ?>
                    <img src="../../uploads/<?= $product['product_image'] ?>" alt="Product" style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px; display: block; margin-bottom: 10px;">
                    <?php endif; ?>
                    <label class="form-label">Upload New Image (optional)</label>
                    <input type="file" name="product_image" class="form-control" accept="image/*">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="your_items.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
