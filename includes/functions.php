<?php
// Common Functions

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Generate unique order number
function generateOrderNumber() {
    return 'ORD' . date('Ymd') . rand(1000, 9999);
}

// Generate unique membership number
function generateMembershipNumber() {
    return 'MEM' . date('Ymd') . rand(1000, 9999);
}

// Format currency
function formatCurrency($amount) {
    return 'Rs. ' . number_format($amount, 2);
}

// Calculate membership end date
function calculateEndDate($startDate, $months) {
    $date = new DateTime($startDate);
    $date->modify("+$months months");
    return $date->format('Y-m-d');
}

// Upload image
function uploadImage($file, $targetDir = null) {
    // Use absolute path to uploads directory
    if ($targetDir === null) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    }
    
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $newFileName = uniqid() . '.' . $imageFileType;
    $targetFile = $targetDir . $newFileName;
    
    // Check if image file is actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return false;
    }
    
    // Check file size (5MB max)
    if ($file["size"] > 5000000) {
        return false;
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $newFileName;
    }
    
    return false;
}

// Redirect with message
function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header("Location: $url");
    exit();
}

// Display message
function displayMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = $_SESSION['message_type'] ?? 'info';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        
        $alertClass = 'alert-info';
        if ($type === 'success') $alertClass = 'alert-success';
        if ($type === 'error') $alertClass = 'alert-danger';
        if ($type === 'warning') $alertClass = 'alert-warning';
        
        echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
                $message
                <button type='button' class='close' data-dismiss='alert'>&times;</button>
              </div>";
    }
}
?>
