<?php
include 'db.php';

function approveUser($userId) {
    global $pdo;

    // Start a try-catch block for error handling
    try {
        $sql = "UPDATE users SET approved = 1 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "User approved successfully.";
        } else {
            echo "No rows updated. Make sure the user exists and is not already approved.";
        }
    } catch (PDOException $e) {
        // Catch and display the error
        echo "Error updating user: " . $e->getMessage();
    }
}
?>
