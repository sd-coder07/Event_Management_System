<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        redirectWithMessage('../user_signup.php', 'Please fill in all fields', 'error');
    }
    
    if (!validateEmail($email)) {
        redirectWithMessage('../user_signup.php', 'Invalid email format', 'error');
    }
    
    if (strlen($password) < 6) {
        redirectWithMessage('../user_signup.php', 'Password must be at least 6 characters', 'error');
    }
    
    $conn = getDBConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        redirectWithMessage('../user_signup.php', 'Email already registered', 'error');
    }
    
    // Hash password
    $hashedPassword = hashPassword($password);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        redirectWithMessage('../user_login.php', 'Registration successful! Please login.', 'success');
    } else {
        redirectWithMessage('../user_signup.php', 'Registration failed. Please try again.', 'error');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: ../user_signup.php");
    exit();
}
?>
