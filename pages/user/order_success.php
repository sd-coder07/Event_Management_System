<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

if (!isset($_SESSION['order_number'])) {
    header("Location: dashboard.php");
    exit();
}

$orderNumber = $_SESSION['order_number'];
unset($_SESSION['order_number']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div style="background: linear-gradient(135deg, #5cb85c, #4cae4c); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div class="auth-card" style="max-width: 600px;">
            <div style="text-align: center; color: #5cb85c; margin-bottom: 30px;">
                <div style="font-size: 80px;">✓</div>
                <h1>Order Placed Successfully!</h1>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                <p style="font-size: 18px; text-align: center;">
                    Your order number is: <strong style="color: #4a7fc1; font-size: 24px;"><?= htmlspecialchars($orderNumber) ?></strong>
                </p>
                <p style="text-align: center; color: #666; margin-top: 10px;">
                    Thank you for your order! You will receive an email confirmation shortly.
                </p>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <a href="order_status.php" class="btn btn-primary" style="width: 100%; padding: 15px;">Track Your Order</a>
                <a href="dashboard.php" class="btn btn-secondary" style="width: 100%; padding: 15px;">Continue Shopping</a>
            </div>
        </div>
    </div>
</body>
</html>
