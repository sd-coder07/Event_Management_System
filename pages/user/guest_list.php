<?php
require_once '../../config/database.php';
require_once '../../config/session.php';
require_once '../../includes/functions.php';

requireUser();

$conn = getDBConnection();
$userId = getCurrentUserId();

// Handle adding guest
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_guest'])) {
    $guestName = sanitizeInput($_POST['guest_name']);
    $guestEmail = sanitizeInput($_POST['guest_email']);
    $guestPhone = sanitizeInput($_POST['guest_phone']);
    
    $stmt = $conn->prepare("INSERT INTO guest_lists (user_id, guest_name, guest_email, guest_phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $guestName, $guestEmail, $guestPhone);
    
    if ($stmt->execute()) {
        redirectWithMessage('guest_list.php', 'Guest added successfully', 'success');
    }
    $stmt->close();
}

// Handle deleting guest
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_guest'])) {
    $guestId = intval($_POST['guest_id']);
    
    $stmt = $conn->prepare("DELETE FROM guest_lists WHERE guest_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $guestId, $userId);
    $stmt->execute();
    $stmt->close();
    
    redirectWithMessage('guest_list.php', 'Guest deleted successfully', 'success');
}

// Get all guests
$guests = $conn->query("SELECT * FROM guest_lists WHERE user_id = $userId ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest List - Event Management System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="nav-left">
                    <a href="dashboard.php" class="btn btn-light">Home</a>
                </div>
                <div class="nav-center">
                    <div class="header-title">Guest List</div>
                </div>
                <div class="nav-right">
                    <a href="../auth/logout.php" class="btn btn-outline">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <?php displayMessage(); ?>
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
            <!-- Add Guest Form -->
            <div class="card">
                <div class="card-header">Add Guest</div>
                <form action="" method="POST">
                    <div class="form-group">
                        <label class="form-label">Guest Name</label>
                        <input type="text" name="guest_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Guest Email</label>
                        <input type="email" name="guest_email" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Guest Phone</label>
                        <input type="tel" name="guest_phone" class="form-control">
                    </div>
                    
                    <button type="submit" name="add_guest" class="btn btn-success" style="width: 100%;">Add Guest</button>
                </form>
            </div>
            
            <!-- Guest List -->
            <div class="card">
                <div class="card-header">Your Guests (<?= $guests->num_rows ?>)</div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Added On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($guest = $guests->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($guest['guest_name']) ?></td>
                                <td><?= htmlspecialchars($guest['guest_email'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($guest['guest_phone'] ?? 'N/A') ?></td>
                                <td><?= date('M d, Y', strtotime($guest['created_at'])) ?></td>
                                <td>
                                    <form action="" method="POST" style="display: inline-block;" onsubmit="return confirmDelete('this guest')">
                                        <input type="hidden" name="guest_id" value="<?= $guest['guest_id'] ?>">
                                        <button type="submit" name="delete_guest" class="btn btn-danger" style="padding: 5px 15px;">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($guests->num_rows === 0): ?>
                <p style="text-align: center; padding: 40px; color: #999;">No guests added yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="../../assets/js/validation.js"></script>
</body>
</html>
<?php closeDBConnection($conn); ?>
