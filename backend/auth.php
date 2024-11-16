<?php
session_start();
include 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Fetch user details
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if (!$user) {
            echo "User not found!";
            exit;
        }

        // Check if user is approved
        if (!$user['approved']) {
            echo "Your account is not approved by the admin.";
            exit;
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            echo "Invalid password!";
            exit;
        }

        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Redirect based on role
        switch ($user['role']) {
            case 'Super Admin':
                header("Location: ../dashboard/superadmin.php");
                break;
            case 'Admin':
                header("Location: ../dashboard/admin.php");
                break;
            case 'User':
                header("Location: ../dashboard/user.php");
                break;
            default:
                echo "Invalid user role!";
                exit;
        }
    } catch (PDOException $e) {
        die("Error during authentication: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
