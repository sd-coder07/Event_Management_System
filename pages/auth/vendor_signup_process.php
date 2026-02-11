<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $category = sanitizeInput($_POST['category']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($category)) {
        redirectWithMessage('../vendor_signup.php', 'Please fill in all fields', 'error');
    }
    
    if (!validateEmail($email)) {
        redirectWithMessage('../vendor_signup.php', 'Invalid email format', 'error');
    }
    
    if (strlen($password) < 6) {
        redirectWithMessage('../vendor_signup.php', 'Password must be at least 6 characters', 'error');
    }
    
    $conn = getDBConnection();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM vendors WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        redirectWithMessage('../vendor_signup.php', 'Email already registered', 'error');
    }
    
    // Hash password
    $hashedPassword = hashPassword($password);
    
    // Insert new vendor
    $stmt = $conn->prepare("INSERT INTO vendors (name, email, password, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $category);
    
    if ($stmt->execute()) {
        redirectWithMessage('../vendor_login.php', 'Registration successful! Please login.', 'success');
    } else {
        redirectWithMessage('../vendor_signup.php', 'Registration failed. Please try again.', 'error');
    }
    
    $stmt->close();
    closeDBConnection($conn);
} else {
    header("Location: ../vendor_signup.php");
    exit();
}
?>
