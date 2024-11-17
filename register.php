<?php
session_start();

// Check if the logged-in user is an Admin or Super Admin
$isAdminOrSuperAdmin = isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Super Admin');

// Display message if set
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;
$messageType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : null;

// Clear the message after displaying
unset($_SESSION['message'], $_SESSION['message_type']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/style.css">

</head>
<body>
    <div class="form-container">
        <div class="form-wrapper">
            <h1 class="text-align-center">Register</h1>

            <?php if ($message): ?>
                <div class="message <?= htmlspecialchars($messageType) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="backend/user_management.php" method="POST" enctype="multipart/form-data" class="form">
                <div class="form-row">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                </div>

                <div class="form-row">
                    <label for="mobile">Mobile:</label>
                    <input type="text" name="mobile" id="mobile" required>
                </div>

                <?php if ($isAdminOrSuperAdmin): ?>
                    <div class="form-row">
                        <label for="role">Role:</label>
                        <select name="role" id="role">
                            <option value="User">User</option>
                            <option value="Admin">Admin</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-row">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-row">
                    <label for="address">Address:</label>
                    <textarea name="address" id="address"></textarea>
                </div>

                <div class="form-row form-row-inline">
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <label><input type="radio" name="gender" value="Male" required> Male</label>
    <label><input type="radio" name="gender" value="Female" required> Female</label>
    <label><input type="radio" name="gender" value="Other" required> Other</label>

                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" name="dob" id="dob" required>
                    </div>
                </div>

                <div class="form-row">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" name="profile_picture" id="profile_picture" required>
                </div>

                <div class="form-row">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="form-row">
                    <button type="submit">Register</button>
                </div>
            </form>

            <p class="text-align-center">
                <a href="/index.php">Back to Login</a>
            </p>
        </div>
    </div>
</body>
</html>
