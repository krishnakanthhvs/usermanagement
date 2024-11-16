<?php
include 'db.php';

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="users.json"');

$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
?>
