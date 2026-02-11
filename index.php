<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-title">Event Management System</div>
            
            <div class="card-header text-center">Welcome</div>
            
            <div style="text-align: center; margin: 30px 0;">
                <p style="margin-bottom: 20px; font-size: 18px; color: #666;">
                    Please select your role to continue
                </p>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <a href="pages/admin_login.php" class="btn btn-primary" style="width: 100%;">
                    Admin Login
                </a>
                
                <a href="pages/vendor_login.php" class="btn btn-primary" style="width: 100%;">
                    Vendor Login
                </a>
                
                <a href="pages/user_login.php" class="btn btn-primary" style="width: 100%;">
                    User Login
                </a>
            </div>
            
            <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p style="color: #666;">Don't have an account?</p>
                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 10px;">
                    <a href="pages/vendor_signup.php" class="btn btn-secondary">Vendor Signup</a>
                    <a href="pages/user_signup.php" class="btn btn-secondary">User Signup</a>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="pages/flowchart.php" class="btn btn-outline" style="color: #4a7fc1; border-color: #4a7fc1;">
                    View System Flowchart
                </a>
            </div>
        </div>
    </div>
</body>
</html>
