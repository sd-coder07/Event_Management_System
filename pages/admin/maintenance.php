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
    <title>Maintenance Menu - Event Management System</title>
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
        <a href="../flowchart.php" class="btn btn-secondary chart-btn">Chart</a>
        
        <?php displayMessage(); ?>
        
        <div class="card">
            <div class="card-header">Maintenance Menu (Admin access only)</div>
            
            <div style="display: flex; flex-direction: column; gap: 20px; max-width: 500px; margin: 30px auto;">
                <div>
                    <h3 style="margin-bottom: 15px;">Membership</h3>
                    <div style="display: flex; gap: 10px;">
                        <a href="add_membership.php" class="btn btn-success" style="flex: 1;">Add</a>
                        <a href="update_membership.php" class="btn btn-primary" style="flex: 1;">Update</a>
                    </div>
                </div>
                
                <div>
                    <h3 style="margin-bottom: 15px;">User Management</h3>
                    <div style="display: flex; gap: 10px;">
                        <a href="manage_users.php" class="btn btn-success" style="flex: 1;">Add</a>
                        <a href="manage_users.php" class="btn btn-primary" style="flex: 1;">Update</a>
                    </div>
                </div>
                
                <div>
                    <h3 style="margin-bottom: 15px;">Vendor Management</h3>
                    <div style="display: flex; gap: 10px;">
                        <a href="manage_vendors.php" class="btn btn-success" style="flex: 1;">Add</a>
                        <a href="manage_vendors.php" class="btn btn-primary" style="flex: 1;">Update</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
