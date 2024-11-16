<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/superadmin.php");
    exit;
}

// Optional: Display a logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    echo "<p>You have been logged out successfully.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <form action="backend/auth.php" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
