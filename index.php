<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard/superadmin.php");
    exit;
}

// Optional: Display a logout success message
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    $logoutMessage = "You have been logged out successfully.";
}

// Capture the error message if it exists
$errorMessage = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-container">
        <h1 class="text-align-center">Login</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <p style="color: red; text-align: center;"><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($logoutMessage)): ?>
            <div class="success-message">
                <p style="color: green; text-align: center;"><?php echo htmlspecialchars($logoutMessage); ?></p>
            </div>
        <?php endif; ?>

        <form action="backend/auth.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray font-bold mb-2">Email:</label>
                <input id="email" type="email" name="email" class="input" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray font-bold mb-2">Password:</label>
                <input id="password" type="password" name="password" class="input" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <div class="text-align-center mt-4">
            <p>Don't have an account? <a href="register.php" class="text-blue">Register here</a></p>
        </div>
    </div>
</div>
</body>
</html>
