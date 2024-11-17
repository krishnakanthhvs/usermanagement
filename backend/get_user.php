<?php
session_start();
include 'db.php';

// Check if admin or super admin is logged in
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['Admin', 'Super Admin'])) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT id, name, email, role, mobile, address, gender, dob, profile_picture FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        header("Content-Type: application/json");
        echo json_encode($user);
    } else {
        header("Content-Type: application/json");
        echo json_encode(['error' => 'User not found']);
    }
} else {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'No ID specified']);
}
?>
