<?php
session_start();
include '../backend/db.php';

// Check if the user is logged in as Admin or Super Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'Admin' && $_SESSION['user_role'] !== 'Super Admin')) {
    header("Location: ../index.php");
    exit;
}

// Get the user ID from the URL
$userId = isset($_GET['id']) ? $_GET['id'] : null;

if ($userId) {
    // Get pending changes for the user
    $sql = "SELECT pending_changes FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['pending_changes']) {
        // Decode pending changes
        $pendingChanges = json_decode($user['pending_changes'], true);

        // Apply changes to the user’s profile
        $sqlUpdate = "UPDATE users SET 
            name = :name, 
            email = :email, 
            mobile = :mobile, 
            address = :address, 
            gender = :gender, 
            dob = :dob, 
            profile_picture = :profile_picture,
            pending_changes = NULL,
            update_approved_by = :approved_by
            WHERE id = :id";

        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':name' => $pendingChanges['name'],
            ':email' => $pendingChanges['email'],
            ':mobile' => $pendingChanges['mobile'],
            ':address' => $pendingChanges['address'],
            ':gender' => $pendingChanges['gender'],
            ':dob' => $pendingChanges['dob'],
            ':profile_picture' => $pendingChanges['profile_picture'] ?? null,
            ':approved_by' => $_SESSION['user_id'],
            ':id' => $userId
        ]);

        // Redirect back to the Admin dashboard with a success message
        $_SESSION['message'] = "User's changes have been approved.";
        $_SESSION['message_type'] = "success";
        header("Location: ../dashboard/superadmin.php");
    } else {
        // If no pending changes, show an error
        $_SESSION['message'] = "No pending changes found for this user.";
        $_SESSION['message_type'] = "error";
        header("Location: ../dashboard/superadmin.php");
    }
}
?>
