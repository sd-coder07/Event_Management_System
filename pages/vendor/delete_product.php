<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['product_id']);
    $vendorId = getCurrentUserId();
    
    $conn = getDBConnection();
    
    // Verify product belongs to this vendor
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ? AND vendor_id = ?");
    $stmt->bind_param("ii", $productId, $vendorId);
    
    if ($stmt->execute()) {
        redirectWithMessage('your_items.php', 'Product deleted successfully', 'success');
    } else {
        redirectWithMessage('your_items.php', 'Failed to delete product', 'error');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: your_items.php");
    exit();
}
?>
