<?php
session_start();

// Check if the logged-in user is an Admin or Super Admin
$isAdminOrSuperAdmin = isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Super Admin');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-container">
        <h1 class="text-align-center">Register</h1>

        <form action="backend/user_management.php" method="POST" enctype="multipart/form-data">
    <label>Name:</label><br>
    <input type="text" name="name" required><br>

    <?php if ($user_role === 'Admin' || $user_role === 'Super Admin'): ?>
        <label>Role:</label><br>
        <select name="role" id="role">
            <option value="User">User</option>
            <option value="Admin">Admin</option>
            <option value="Super Admin">Super Admin</option>
        </select><br>
    <?php endif; ?>

    <label>Mobile:</label><br>
    <input type="text" name="mobile" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Address:</label><br>
    <textarea name="address"></textarea><br>

    <label>Gender:</label><br>
    <input type="radio" name="gender" value="Male" required> Male
    <input type="radio" name="gender" value="Female" required> Female
    <input type="radio" name="gender" value="Other" required> Other<br>

    <label>Date of Birth:</label><br>
    <input type="date" name="date_of_birth" required><br>

    <label>Profile Picture:</label><br>
    <input type="file" name="profile_picture" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br>

    <button type="submit">Register</button>
</form>

    </div>
</div>
</body>
</html>
