<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireVendor();

$conn = getDBConnection();
$vendorId = getCurrentUserId();

// Get user requests for this vendor
$requests = $conn->query("SELECT r.*, u.name as user_name, u.email as user_email 
                          FROM user_requests r 
                          JOIN users u ON r.user_id = u.user_id 
                          WHERE r.vendor_id = $vendorId 
                          ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Requests - Event Management System</title>
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
                    <div class="header-title">User Requests</div>
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
            <div class="card-header">Item Requests from Users</div>
            <div class="product-grid">
                <?php while ($request = $requests->fetch_assoc()): ?>
                <div class="product-card">
                    <h3>Item <?= $request['request_id'] ?></h3>
                    <div style="margin: 15px 0;">
                        <p><strong>Requested By:</strong> <?= htmlspecialchars($request['user_name']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($request['user_email']) ?></p>
                        <p><strong>Item Name:</strong> <?= htmlspecialchars($request['item_name']) ?></p>
                        <p><strong>Description:</strong> <?= htmlspecialchars($request['description'] ?? 'N/A') ?></p>
                        <p><strong>Status:</strong> 
                            <span style="padding: 5px 10px; border-radius: 5px; background-color: <?= $request['request_status'] === 'pending' ? '#ffc107' : ($request['request_status'] === 'approved' ? '#5cb85c' : '#d9534f') ?>; color: white;">
                                <?= ucfirst($request['request_status']) ?>
                            </span>
                        </p>
                        <p><strong>Requested On:</strong> <?= date('M d, Y', strtotime($request['created_at'])) ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <?php if ($requests->num_rows === 0): ?>
            <p style="text-align: center; padding: 40px; color: #999;">No user requests yet</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>
