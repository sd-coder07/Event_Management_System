<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Flowchart - Event Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .flowchart-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 1400px;
            margin: 20px auto;
        }
        .flowchart-image {
            width: 100%;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
    </style>
</head>
<body style="background: #f8f9fa;">
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="javascript:history.back()" class="btn btn-light">Back</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">System Flowchart</div>
                </div>
                <div class="nav-right">
                    <a href="../index.php" class="btn btn-outline">Home</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="flowchart-container">
            <h1 style="text-align: center; color: #4a7fc1; margin-bottom: 30px;">
                Event Management System - Navigation Flow
            </h1>
            
            <div style="background: #e3f2fd; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                <h2 style="color: #1976d2;">System Overview</h2>
                <p style="line-height: 1.8;">
                    This Event Management System has three main user roles:
                </p>
                <ul style="line-height: 2;">
                    <li><strong>Admin:</strong> Full system control - manage users, vendors, memberships</li>
                    <li><strong>Vendor:</strong> Add products, manage inventory, view orders</li>
                    <li><strong>User:</strong> Browse vendors, shop products, place orders, track status</li>
                </ul>
            </div>
            
            <div style="background: #fff3cd; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
                <h2 style="color: #856404;">Navigation Guide</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 20px;">
                    <div>
                        <h3 style="color: #4a7fc1;">Admin Path</h3>
                        <ol style="line-height: 2;">
                            <li>Index → Admin Login</li>
                            <li>Admin Dashboard</li>
                            <li>Maintenance Menu</li>
                            <li>User/Vendor Management</li>
                            <li>Add/Update Memberships</li>
                        </ol>
                    </div>
                    
                    <div>
                        <h3 style="color: #5cb85c;">Vendor Path</h3>
                        <ol style="line-height: 2;">
                            <li>Index → Vendor Signup/Login</li>
                            <li>Vendor Dashboard</li>
                            <li>Add New Items</li>
                            <li>View Your Items</li>
                            <li>Product Status & Orders</li>
                            <li>View Transactions</li>
                        </ol>
                    </div>
                    
                    <div>
                        <h3 style="color: #17a2b8;">User Path</h3>
                        <ol style="line-height: 2;">
                            <li>Index → User Signup/Login</li>
                            <li>User Dashboard</li>
                            <li>Browse Vendors by Category</li>
                            <li>Add Products to Cart</li>
                            <li>Manage Guest List</li>
                            <li>Checkout & Place Order</li>
                            <li>Track Order Status</li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <div style="background: #d4edda; padding: 20px; border-radius: 10px;">
                <h2 style="color: #155724;">Key Features</h2>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 15px;">
                    <div>✓ Role-based access control</div>
                    <div>✓ Secure password hashing</div>
                    <div>✓ Session management</div>
                    <div>✓ Form validations</div>
                    <div>✓ Shopping cart functionality</div>
                    <div>✓ Order tracking system</div>
                    <div>✓ Membership management</div>
                    <div>✓ Product management</div>
                    <div>✓ Guest list management</div>
                    <div>✓ Multi-vendor support</div>
                    <div>✓ Category-based browsing</div>
                    <div>✓ Responsive design</div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="../index.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 18px;">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
