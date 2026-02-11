<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();

// Get all categories
$categories = $conn->query("SELECT DISTINCT category FROM vendors WHERE status = 'active'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div style="background: linear-gradient(135deg, #4a7fc1, #5c8fd6); min-height: 100vh;">
        <header style="padding: 20px 0;">
            <div class="container">
                <div class="welcome-message" style="background: white; color: #4a7fc1; text-align: center;">
                    <h2>WELCOME USER</h2>
                </div>
                
                <div class="navbar" style="margin-top: 20px;">
                    <div class="nav-left" style="position: relative;">
                        <div class="dropdown">
                            <button class="btn btn-light" id="dropdownBtn" style="display: flex; align-items: center; gap: 10px;">
                                Drop Down
                                <span style="font-size: 12px;">▼</span>
                            </button>
                            <div id="dropdownMenu" class="dropdown-menu" style="display: none; position: absolute; top: 100%; left: 0; background: white; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); min-width: 200px; z-index: 1000; margin-top: 5px;">
                                <?php while ($cat = $categories->fetch_assoc()): ?>
                                <a href="browse_vendors.php?category=<?= urlencode($cat['category']) ?>" style="display: block; padding: 15px; color: #333; text-decoration: none; border-bottom: 1px solid #eee;">
                                    * <?= htmlspecialchars($cat['category']) ?>
                                </a>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <div class="nav-center" style="display: flex; gap: 10px;">
                        <a href="browse_vendors.php" class="btn btn-light">Vendor</a>
                        <a href="cart.php" class="btn btn-light">Cart</a>
                        <a href="guest_list.php" class="btn btn-light">Guest List</a>
                        <a href="order_status.php" class="btn btn-light">Order Status</a>
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
                <div class="card-header">Select Service Category</div>
                <div class="product-grid">
                    <?php
                    $conn2 = getDBConnection();
                    $categories2 = $conn2->query("SELECT DISTINCT category FROM vendors WHERE status = 'active'");
                    while ($category = $categories2->fetch_assoc()):
                    ?>
                    <a href="browse_vendors.php?category=<?= urlencode($category['category']) ?>" class="product-card" style="text-decoration: none; color: inherit;">
                        <div style="text-align: center; padding: 40px;">
                            <h2><?= htmlspecialchars($category['category']) ?></h2>
                            <p style="color: #666; margin-top: 10px;">Browse <?= $category['category'] ?> vendors</p>
                        </div>
                    </a>
                    <?php endwhile; ?>
                    <?php closeDBConnection($conn2); ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.getElementById('dropdownBtn').addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = document.getElementById('dropdownMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        });
        
        document.addEventListener('click', function() {
            document.getElementById('dropdownMenu').style.display = 'none';
        });
    </script>
</body>
</html>
<?php closeDBConnection($conn); ?>
