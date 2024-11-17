<?php
session_start();
include 'db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Fetch user from the database using $pdo
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql); // Use $pdo from db.php
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['approved'] != 1) {
                // User not approved
                header("Location: ../index.php?error=Your account is yet to be activated by the admin.");
                exit;
            }

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to appropriate dashboard
            if ($user['role'] === 'Super Admin') {
                header("Location: ../dashboard/superadmin.php");
            } elseif ($user['role'] === 'Admin') {
                header("Location: ../dashboard/admin.php");
            } else {
                header("Location: ../dashboard/user.php");
            }
            exit;
        } else {
            // Invalid credentials
            header("Location: ../index.php?error=Invalid email or password.");
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>
