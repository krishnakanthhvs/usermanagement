<?php
session_start();
include 'db.php';

// Check if admin or super admin is logged in
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['Admin', 'Super Admin'])) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

$sql = "SELECT id, name, email, role, mobile, address, gender, dob, profile_picture FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($users);
?>
