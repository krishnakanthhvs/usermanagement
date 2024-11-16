<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $mobile = $_POST['mobile'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle file upload
    $profile_picture = '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $upload_dir = '../uploads/';
        $profile_picture = basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $profile_picture);
    }

    try {
        $sql = "INSERT INTO users (name, email, role, mobile, profile_picture, password, approved)
                VALUES (:name, :email, :role, :mobile, :profile_picture, :password, 1)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':mobile' => $mobile,
            ':profile_picture' => $profile_picture,
            ':password' => $password,
        ]);
        header("Location: ../dashboard/superadmin.php");
    } catch (PDOException $e) {
        die("Error adding user: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
