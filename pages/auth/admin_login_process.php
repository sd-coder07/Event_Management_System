<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = sanitizeInput($_POST['user_id']);
    $password = sanitizeInput($_POST['password']);
    
    // Validate inputs
    if (empty($userId) || empty($password)) {
        redirectWithMessage('../admin_login.php', 'Please fill in all fields', 'error');
    }
    
    $conn = getDBConnection();
    
    // Check admin credentials
    $stmt = $conn->prepare("SELECT * FROM admin WHERE user_id = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        
        if (verifyPassword($password, $admin['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_name'] = 'Admin';
            
            header("Location: ../admin/dashboard.php");
            exit();
        } else {
            redirectWithMessage('../admin_login.php', 'Invalid password', 'error');
        }
    } else {
        redirectWithMessage('../admin_login.php', 'Admin not found', 'error');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: ../admin_login.php");
    exit();
}
?>
