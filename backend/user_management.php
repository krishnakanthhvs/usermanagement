<?php
include 'db.php';
session_start();  // Start the session to check the user's role

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is an admin or super admin
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'User'; // Default to 'User' if not logged in

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    
    // If the user is an admin or super admin, allow role selection, otherwise default to 'User'
    $role = ($user_role === 'Admin' || $user_role === 'Super Admin') && isset($_POST['role']) ? $_POST['role'] : 'User';
    
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle file upload
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $upload_dir = '../uploads/';
        $profile_picture = basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_picture);
    }

    try {
        $sql = "INSERT INTO users (name, role, mobile, email, address, gender, date_of_birth, profile_picture, password, approved)
                VALUES (:name, :role, :mobile, :email, :address, :gender, :date_of_birth, :profile_picture, :password, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':role' => $role,
            ':mobile' => $mobile,
            ':email' => $email,
            ':address' => $address,
            ':gender' => $gender,
            ':date_of_birth' => $date_of_birth,
            ':profile_picture' => $profile_picture,
            ':password' => $password,
        ]);

        echo "User registered successfully!";
    } catch (PDOException $e) {
        die("Error during registration: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
