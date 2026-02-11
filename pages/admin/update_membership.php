<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireAdmin();

$conn = getDBConnection();

$membership = null;
$vendor = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['search'])) {
        $membershipNumber = sanitizeInput($_POST['membership_number']);
        
        $stmt = $conn->prepare("SELECT m.*, v.name, v.category FROM memberships m JOIN vendors v ON m.vendor_id = v.vendor_id WHERE m.membership_number = ?");
        $stmt->bind_param("s", $membershipNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $membership = $result->fetch_assoc();
        } else {
            $_SESSION['message'] = 'Membership not found';
            $_SESSION['message_type'] = 'error';
        }
        $stmt->close();
    } elseif (isset($_POST['extend'])) {
        $membershipId = intval($_POST['membership_id']);
        $extensionMonths = intval($_POST['extension_months']);
        
        $stmt = $conn->prepare("SELECT end_date, duration_months FROM memberships WHERE membership_id = ?");
        $stmt->bind_param("i", $membershipId);
        $stmt->execute();
        $result = $stmt->get_result();
        $membership = $result->fetch_assoc();
        
        $newEndDate = calculateEndDate($membership['end_date'], $extensionMonths);
        $newDuration = $membership['duration_months'] + $extensionMonths;
        
        $updateStmt = $conn->prepare("UPDATE memberships SET end_date = ?, duration_months = ? WHERE membership_id = ?");
        $updateStmt->bind_param("sii", $newEndDate, $newDuration, $membershipId);
        $updateStmt->execute();
        $updateStmt->close();
        $stmt->close();
        
        redirectWithMessage('update_membership.php', 'Membership extended successfully!', 'success');
    } elseif (isset($_POST['cancel'])) {
        $membershipId = intval($_POST['membership_id']);
        
        $stmt = $conn->prepare("UPDATE memberships SET status = 'cancelled' WHERE membership_id = ?");
        $stmt->bind_param("i", $membershipId);
        $stmt->execute();
        $stmt->close();
        
        redirectWithMessage('update_membership.php', 'Membership cancelled successfully!', 'success');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Membership - Event Management System</title>
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
                    <div class="header-title">Update Membership for Vendor</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <!-- Search Membership -->
        <div class="card">
            <div class="card-header">Search Membership</div>
            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Membership Number *</label>
                    <input type="text" name="membership_number" class="form-control" placeholder="Enter Membership Number" required>
                </div>
                <button type="submit" name="search" class="btn btn-primary">Search</button>
            </form>
        </div>
        
        <?php if ($membership): ?>
        <!-- Membership Details -->
        <div class="card">
            <div class="card-header">Membership Details</div>
            <table>
                <tr>
                    <th>Membership Number:</th>
                    <td><?= htmlspecialchars($membership['membership_number']) ?></td>
                </tr>
                <tr>
                    <th>Vendor Name:</th>
                    <td><?= htmlspecialchars($membership['name']) ?></td>
                </tr>
                <tr>
                    <th>Category:</th>
                    <td><?= htmlspecialchars($membership['category']) ?></td>
                </tr>
                <tr>
                    <th>Start Date:</th>
                    <td><?= date('M d, Y', strtotime($membership['start_date'])) ?></td>
                </tr>
                <tr>
                    <th>End Date:</th>
                    <td><?= date('M d, Y', strtotime($membership['end_date'])) ?></td>
                </tr>
                <tr>
                    <th>Duration:</th>
                    <td><?= $membership['duration_months'] ?> months</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><strong><?= ucfirst($membership['status']) ?></strong></td>
                </tr>
            </table>
        </div>
        
        <?php if ($membership['status'] !== 'cancelled'): ?>
        <!-- Extend Membership -->
        <div class="card">
            <div class="card-header">Extend Membership</div>
            <form action="" method="POST">
                <input type="hidden" name="membership_id" value="<?= $membership['membership_id'] ?>">
                <div class="form-group">
                    <label class="form-label">Extension Duration</label>
                    <div class="radio-group">
                        <div class="radio-item">
                            <input type="radio" id="ext6" name="extension_months" value="6" checked>
                            <label for="ext6">6 Months Extension</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="ext12" name="extension_months" value="12">
                            <label for="ext12">1 Year Extension</label>
                        </div>
                        <div class="radio-item">
                            <input type="radio" id="ext24" name="extension_months" value="24">
                            <label for="ext24">2 Years Extension</label>
                        </div>
                    </div>
                </div>
                <button type="submit" name="extend" class="btn btn-success">Extend Membership</button>
            </form>
        </div>
        
        <!-- Cancel Membership -->
        <div class="card">
            <div class="card-header">Cancel Membership</div>
            <form action="" method="POST" onsubmit="return confirmAction('cancel this membership')">
                <input type="hidden" name="membership_id" value="<?= $membership['membership_id'] ?>">
                <p style="color: #d9534f;">Warning: This action cannot be undone.</p>
                <button type="submit" name="cancel" class="btn btn-danger">Cancel Membership</button>
            </form>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
<?php closeDBConnection($conn); ?>
