<?php
// Session Management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_type']);
}

// Check if user is admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['user_type'] === 'admin';
}

// Check if user is vendor
function isVendor() {
    return isLoggedIn() && $_SESSION['user_type'] === 'vendor';
}

// Check if user is regular user
function isUser() {
    return isLoggedIn() && $_SESSION['user_type'] === 'user';
}

// Require admin access
function requireAdmin() {
    if (!isAdmin()) {
        header("Location: /index.php");
        exit();
    }
}

// Require vendor access
function requireVendor() {
    if (!isVendor()) {
        header("Location: /index.php");
        exit();
    }
}

// Require user access
function requireUser() {
    if (!isUser()) {
        header("Location: /index.php");
        exit();
    }
}

// Require any logged in user
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /index.php");
        exit();
    }
}

// Logout function
function logout() {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user type
function getUserType() {
    return $_SESSION['user_type'] ?? null;
}

// Get current user name
function getUserName() {
    return $_SESSION['user_name'] ?? 'User';
}
?>
