<?php
include 'db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Super Admin') && isset($_POST['role']) ? $_POST['role'] : 'User';
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle file upload
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $upload_dir = '../uploads/';
        $profile_picture = basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_picture);
    }

    try {
        $sql = "INSERT INTO users (name, role, mobile, email, address, gender, dob, profile_picture, password, approved)
                VALUES (:name, :role, :mobile, :email, :address, :gender, :dob, :profile_picture, :password, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':role' => $role,
            ':mobile' => $mobile,
            ':email' => $email,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob,
            ':profile_picture' => $profile_picture,
            ':password' => $password,
        ]);

        $_SESSION['message'] = "User registered successfully!";
        $_SESSION['message_type'] = "success";
    } catch (PDOException $e) {
        $_SESSION['message'] = "Error during registration: " . $e->getMessage();
        $_SESSION['message_type'] = "error";
    }
    header("Location: ../register.php");
    exit;
} else {
    $_SESSION['message'] = "Invalid request method.";
    $_SESSION['message_type'] = "error";
    header("Location: ../register.php");
    exit;
}
?>
