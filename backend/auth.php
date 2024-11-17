<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture the login form inputs
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Query the database for the user with the provided email
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // If user exists and the password matches
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'Super Admin') {
                header("Location: ../dashboard/superadmin.php");
            } elseif ($user['role'] == 'Admin') {
                header("Location: ../dashboard/admin.php");
            } else {
                header("Location: ../dashboard/user.php");
            }
            exit;
        } else {
            // Redirect back with error message
            header("Location: ../index.php?error=Invalid credentials");
            exit;
        }
    } catch (PDOException $e) {
        // Handle error gracefully and redirect with error message
        header("Location: ../index.php?error=An error occurred. Please try again.");
        exit;
    }
} else {
    // If the form is not submitted, redirect to the login page
    header("Location: ../index.php");
    exit;
}
?>
