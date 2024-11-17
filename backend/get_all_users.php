<?php
include 'db.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Get the current page or default to 1
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Get the limit or default to 10
$offset = ($page - 1) * $limit; // Calculate the offset

// Query to fetch users with pagination
$sql = "SELECT id, name, email, role, mobile, address, gender, dob, profile_picture, approved FROM users LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to get the total number of users for pagination
$sqlTotal = "SELECT COUNT(*) FROM users";
$totalUsers = $pdo->query($sqlTotal)->fetchColumn();

$response = [
    'users' => $users,
    'total_users' => $totalUsers,
];

echo json_encode($response);
?>
