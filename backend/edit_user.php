<?php
session_start();
include 'db.php'; // Ensure the DB connection is correct

// Ensure the ID is passed and is valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("Invalid or missing user ID.");
}

// Fetch user details from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch();

    if (!$user) {
        die("User not found!");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle form submission to update user data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $profilePicture = $user['profile_picture']; // Default to existing picture

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
            $profilePicture = $fileName;
        }
    }

    // Update user in the database
    try {
        $sql = "UPDATE users SET 
                name = :name, 
                email = :email, 
                role = :role, 
                mobile = :mobile, 
                address = :address, 
                gender = :gender, 
                dob = :dob, 
                profile_picture = :profile_picture 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':mobile' => $mobile,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob,
            ':profile_picture' => $profilePicture,
            ':id' => $id
        ];

        $stmt->execute($params);

        $_SESSION['message'] = 'User updated successfully!';
        $_SESSION['message_type'] = 'success';

        // Redirect back to the same page with success message
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id); 
        exit;
    } catch (PDOException $e) {
        $_SESSION['message'] = 'Error updating user: ' . $e->getMessage();
        $_SESSION['message_type'] = 'error';

        // Redirect back with error message
        header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id); 
        exit;
    }
}
?>

<html>
<head>
    <title>Update User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <!-- Header Section -->
    <div class="header mb-4">
        <h1>Super Admin Dashboard</h1>
        <div class="user-info">
            <span id="greeting">Hi, <span id="userName">Admin</span></span>
            <button class="logout-btn" id="logout">Logout</button>
        </div>
    </div>

    <!-- User Dashboard and Form -->
    <div class="user-dashboard">
    <?php
    // Display the success or error message
    if (isset($_SESSION['message'])) {
        echo '<div class="message ' . ($_SESSION['message_type'] ?? 'error') . '">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);  // Clear the message after displaying
    }
    ?>
        <form action="edit_user.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data" class="form">
            <div class="form-row">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-row">
                <label for="mobile">Mobile:</label>
                <input type="text" name="mobile" id="mobile" value="<?= htmlspecialchars($user['mobile']) ?>" required>
            </div>

            <div class="form-row">
                <label for="role">Role:</label>
                <select name="role" id="role">
                    <option value="User" <?= $user['role'] == 'User' ? 'selected' : '' ?>>User</option>
                    <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="Super Admin" <?= $user['role'] == 'Super Admin' ? 'selected' : '' ?>>Super Admin</option>
                </select>
            </div>

            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-row">
                <label for="address">Address:</label>
                <textarea name="address" id="address"><?= htmlspecialchars($user['address']) ?></textarea>
            </div>

            <div class="form-row form-row-inline">
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <label><input type="radio" name="gender" value="Male" <?= $user['gender'] == 'Male' ? 'checked' : '' ?> required> Male</label>
                    <label><input type="radio" name="gender" value="Female" <?= $user['gender'] == 'Female' ? 'checked' : '' ?> required> Female</label>
                    <label><input type="radio" name="gender" value="Other" <?= $user['gender'] == 'Other' ? 'checked' : '' ?> required> Other</label>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($user['dob']) ?>" required>
                </div>
            </div>

            <div class="form-row">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">
            </div>

            <div class="form-row">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="form-row">
                <button type="submit">Update User</button>
            </div>
        </form>

        <!-- Back to All Users Link -->
        <div class="form-row">
            <a href="../dashboard/superadmin.php" class="back-to-users">Back to All Users</a>
        </div>
    </div>
</body>
</html>
