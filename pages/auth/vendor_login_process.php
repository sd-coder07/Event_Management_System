<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = sanitizeInput($_POST['user_id']);
    $password = sanitizeInput($_POST['password']);
    
    // Validate inputs
    if (empty($userId) || empty($password)) {
        redirectWithMessage('../vendor_login.php', 'Please fill in all fields', 'error');
    }
    
    $conn = getDBConnection();
    
    // Check vendor credentials by email or name
    $stmt = $conn->prepare("SELECT * FROM vendors WHERE email = ? OR name = ?");
    $stmt->bind_param("ss", $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $vendor = $result->fetch_assoc();
        
        if ($vendor['status'] === 'inactive') {
            redirectWithMessage('../vendor_login.php', 'Your account has been deactivated', 'error');
        }
        
        if (verifyPassword($password, $vendor['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $vendor['vendor_id'];
            $_SESSION['user_type'] = 'vendor';
            $_SESSION['user_name'] = $vendor['name'];
            $_SESSION['vendor_category'] = $vendor['category'];
            
            header("Location: ../vendor/dashboard.php");
            exit();
        } else {
            redirectWithMessage('../vendor_login.php', 'Invalid password', 'error');
        }
    } else {
        redirectWithMessage('../vendor_login.php', 'Vendor not found', 'error');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: ../vendor_login.php");
    exit();
}
?>
