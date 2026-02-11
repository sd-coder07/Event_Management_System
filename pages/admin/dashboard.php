<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Event Management System</title>
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
                    <div class="header-title">Welcome Admin</div>
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
            <div class="card-header">Admin Control Panel</div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px;">
                <a href="maintenance.php" class="btn btn-primary" style="padding: 40px; font-size: 20px;">
                    Maintenance Menu
                </a>
                <a href="manage_users.php" class="btn btn-success" style="padding: 40px; font-size: 20px;">
                    Maintain User
                </a>
                <a href="manage_vendors.php" class="btn btn-success" style="padding: 40px; font-size: 20px;">
                    Maintain Vendor
                </a>
            </div>
        </div>
    </div>
</body>
</html>
