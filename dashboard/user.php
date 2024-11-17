<?php
session_start();
include '../backend/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'User') {
    header("Location: ../index.php");
    exit;
}

// Fetch user data
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle profile updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'mobile' => $_POST['mobile'],
        'address' => $_POST['address'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob']
    ];

    // Handle profile picture update
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath);
        $updatedData['profile_picture'] = $fileName;
    }

    // Save the updated data as pending changes
    $sql = "UPDATE users SET pending_changes = :pending_changes, update_approved_by = NULL WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':pending_changes' => json_encode($updatedData),
        ':id' => $userId
    ]);

    echo "<p>Your profile update is pending approval.</p>";
    header("Refresh:2; url=user.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="header">
        <h1>User Dashboard</h1>
        <div class="user-info">
            <img src="../uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" id="profilePicture" width="40">
            <span id="loggedInUser"><?= htmlspecialchars($user['name']) ?></span>
            <button class="logout-btn" id="logoutButton">Logout</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="user-info">
            <h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>
            <!-- <img src="../uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" width="150" class="profile-picture"> -->
        </div>
        <div class="user-dashboard">
        
        
        <form method="POST" enctype="multipart/form-data">
            <label>Name:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" <?= $user['pending_changes'] ? 'readonly' : '' ?>><br>

            <label>Email:</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" <?= $user['pending_changes'] ? 'readonly' : '' ?>><br>

            <label>Mobile:</label><br>
            <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" <?= $user['pending_changes'] ? 'readonly' : '' ?>><br>

            <label>Address:</label><br>
            <textarea name="address" <?= $user['pending_changes'] ? 'readonly' : '' ?>><?= htmlspecialchars($user['address']) ?></textarea><br>

            <label>Gender:</label><br>
            <select name="gender" <?= $user['pending_changes'] ? 'disabled' : '' ?>>
                <option value="Male" <?= $user['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $user['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
            </select><br>

            <label>Date of Birth:</label><br>
            <input type="date" name="dob" value="<?= htmlspecialchars($user['dob']) ?>" <?= $user['pending_changes'] ? 'readonly' : '' ?>><br>

            <label>Profile Picture:</label><br>
            <!-- Show current profile picture if available -->
            <?php if ($user['profile_picture']): ?>
                <img src="../uploads/<?= htmlspecialchars($user['profile_picture']) ?>" alt="Profile Picture" width="100"><br>
            <?php endif; ?>
            <input type="file" name="profile_picture" <?= $user['pending_changes'] ? 'disabled' : '' ?>><br>

            <button type="submit" <?= $user['pending_changes'] ? 'disabled' : '' ?>>Save Changes</button>
        </form>

            <?php if ($user['pending_changes']): ?>
                <p>Your updates are pending approval by the Admin or Super Admin.</p>
            <?php endif; ?>
        </div>
    </div>


    <script>
        // Logout functionality
        document.getElementById("logoutButton").addEventListener("click", function() {
            window.location.href = "../backend/logout.php";
        });
    </script>
</body>
</html>
