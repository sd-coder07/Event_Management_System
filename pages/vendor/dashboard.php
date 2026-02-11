<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header style="background: linear-gradient(135deg, #4a7fc1, #5c8fd6);">
        <div class="container">
            <div class="welcome-message" style="background: rgba(255,255,255,0.1); color: white; text-align: center; margin-bottom: 20px;">
                <h2>Welcome</h2>
                <p style="font-size: 20px; font-weight: bold;">Vendor</p>
            </div>
            
            <div class="navbar">
                <div class="nav-left">
                    <a href="your_items.php" class="btn btn-light">Your Item</a>
                </div>
                <div class="nav-center" style="display: flex; gap: 10px;">
                    <a href="add_item.php" class="btn btn-light">Add New Item</a>
                    <a href="transactions.php" class="btn btn-light">Transaction</a>
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
            <div class="card-header">Vendor Dashboard</div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                <a href="add_item.php" class="btn btn-success" style="padding: 40px; font-size: 18px;">
                    Add New Item
                </a>
                <a href="your_items.php" class="btn btn-primary" style="padding: 40px; font-size: 18px;">
                    View Your Items
                </a>
                <a href="product_status.php" class="btn btn-primary" style="padding: 40px; font-size: 18px;">
                    Product Status
                </a>
                <a href="request_items.php" class="btn btn-primary" style="padding: 40px; font-size: 18px;">
                    User Requests
                </a>
            </div>
        </div>
    </div>
</body>
</html>
