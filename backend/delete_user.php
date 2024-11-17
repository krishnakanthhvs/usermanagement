<?php
include 'db.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Delete the user
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the dashboard
    header('Location: ../admin/dashboard.php');
    exit();
}
?>
