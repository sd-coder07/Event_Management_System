<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Login - Event Management System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <a href="../index.php" class="btn btn-light" style="margin-bottom: 20px; float: right;">BACK</a>
            <div style="clear: both;"></div>
            
            <div class="header-title" style="background-color: #4a7fc1; color: white; padding: 15px; border-radius: 5px; margin-bottom: 30px;">
                Event Management System
            </div>
            
            <?php
            session_start();
            if (isset($_SESSION['message'])) {
                $type = $_SESSION['message_type'] ?? 'info';
                $alertClass = 'alert-' . $type;
                echo "<div class='alert $alertClass'>{$_SESSION['message']}</div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>
            
            <form action="auth/vendor_login_process.php" method="POST" onsubmit="return validateLoginForm(this)">
                <div class="form-group">
                    <label class="form-label">User Id</label>
                    <input type="text" name="user_id" class="form-control" placeholder="Vendor">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Vendor">
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: center; margin-top: 30px;">
                    <a href="../index.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
            
            <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                <p style="color: #666;">Don't have an account?</p>
                <a href="vendor_signup.php" class="btn btn-success">Sign Up</a>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/validation.js"></script>
</body>
</html>
