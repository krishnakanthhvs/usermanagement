<?php
include 'db.php';

// Start session to maintain login information (if needed)
session_start();

if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'Admin' && $_SESSION['user_role'] !== 'Super Admin')) {
    die("Unauthorized access.");
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Sanitize the user ID

    try {
        $sql = "UPDATE users SET approved = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Success message
            $_SESSION['message'] = "User approved successfully.";
            $_SESSION['message_type'] = "success";
        } else {
            // Error message if no rows were updated
            $_SESSION['message'] = "User not found or already approved.";
            $_SESSION['message_type'] = "error";
        }
    } catch (PDOException $e) {
        // Handle and log the error, then display a user-friendly message
        $_SESSION['message'] = "Error approving user: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }

    // Conditional redirect based on user role
    if ($_SESSION['user_role'] === 'Super Admin') {
        header("Location: ../dashboard/superadmin.php");
    } else {
        header("Location: ../dashboard/admin.php");
    }

    exit;
} else {
    $_SESSION['message'] = "No user ID provided.";
    $_SESSION['message_type'] = "error";
    // Redirect to the dashboard based on user role
    if ($_SESSION['user_role'] === 'Super Admin') {
        header("Location: ../dashboard/superadmin.php");
    } else {
        header("Location: ../dashboard/admin.php");
    }
    exit;
}
?>
