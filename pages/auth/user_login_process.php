<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = sanitizeInput($_POST['user_id']);
    $password = sanitizeInput($_POST['password']);
    
    // Validate inputs
    if (empty($userId) || empty($password)) {
        redirectWithMessage('../user_login.php', 'Please fill in all fields', 'error');
    }
    
    $conn = getDBConnection();
    
    // Check user credentials by email or name
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
    $stmt->bind_param("ss", $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if ($user['status'] === 'inactive') {
            redirectWithMessage('../user_login.php', 'Your account has been deactivated', 'error');
        }
        
        if (verifyPassword($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = 'user';
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            header("Location: ../user/dashboard.php");
            exit();
        } else {
            redirectWithMessage('../user_login.php', 'Invalid password', 'error');
        }
    } else {
        redirectWithMessage('../user_login.php', 'User not found', 'error');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: ../user_login.php");
    exit();
}
?>
