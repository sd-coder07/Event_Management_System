<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireAdmin();

$conn = getDBConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendorId = intval($_POST['vendor_id']);
    $durationMonths = intval($_POST['duration_months']);
    $startDate = date('Y-m-d');
    $endDate = calculateEndDate($startDate, $durationMonths);
    $membershipNumber = generateMembershipNumber();
    
    $stmt = $conn->prepare("INSERT INTO memberships (vendor_id, membership_number, start_date, end_date, duration_months) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssi", $vendorId, $membershipNumber, $startDate, $endDate, $durationMonths);
    
    if ($stmt->execute()) {
        redirectWithMessage('add_membership.php', 'Membership added successfully!', 'success');
    } else {
        redirectWithMessage('add_membership.php', 'Failed to add membership', 'error');
    }
    $stmt->close();
}

$vendors = $conn->query("SELECT * FROM vendors WHERE status = 'active'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Membership - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="maintenance.php" class="btn btn-light">Home</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">Add Membership for Vendor</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <div class="card">
            <div class="card-header">Add Membership for Vendor</div>
            <p style="color: #666; margin-bottom: 20px;">All fields mandatory. Select membership duration (6 months is selected by default).</p>
            
            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Select Vendor *</label>
                    <select name="vendor_id" class="form-control" required>
                        <option value="">-- Select Vendor --</option>
                        <?php while ($vendor = $vendors->fetch_assoc()): ?>
                        <option value="<?= $vendor['vendor_id'] ?>">
                            <?= htmlspecialchars($vendor['name']) ?> (<?= $vendor['category'] ?>)
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Membership Duration *</label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="duration6" name="duration_months" value="6" checked>
                            <label for="duration6">6 Months</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="duration12" name="duration_months" value="12">
                            <label for="duration12">1 Year (12 Months)</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="duration24" name="duration_months" value="24">
                            <label for="duration24">2 Years (24 Months)</label>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="btn btn-success">Add Membership</button>
                    <a href="maintenance.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php closeDBConnection($conn); ?>
