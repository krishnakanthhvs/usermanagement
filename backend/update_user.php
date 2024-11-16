<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $mobile = $_POST['mobile'];

    try {
        $sql = "UPDATE users SET name = :name, email = :email, role = :role, mobile = :mobile WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':mobile' => $mobile,
            ':id' => $id,
        ]);
        header("Location: ../dashboard/superadmin.php");
    } catch (PDOException $e) {
        die("Error updating user: " . $e->getMessage());
    }
} else {
    echo "Invalid request method.";
}
?>
