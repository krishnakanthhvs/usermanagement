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
    <div class="form-container">
        <div class="form-wrapper">
            <h1 class="text-align-center">Login</h1>

            <?php if (!empty($errorMessage)): ?>
                <div class="form-row">
                    <p class="error-message" style="color: red; text-align: center;"><?php echo htmlspecialchars($errorMessage); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($logoutMessage)): ?>
                <div class="form-row">
                    <p class="success-message" style="color: green; text-align: center;"><?php echo htmlspecialchars($logoutMessage); ?></p>
                </div>
            <?php endif; ?>

            <form action="backend/auth.php" method="POST" class="form">
                <div class="form-row">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <div class="form-row">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="form-row">
                    <button type="submit">Login</button>
                </div>
            </form>

            <div class="text-align-center mt-4">
                <p>Don't have an account? <a href="register.php" class="text-blue">Register here</a></p>
            </div>
        </div>
    </div>
</body>
</html>
