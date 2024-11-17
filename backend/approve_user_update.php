<?php
session_start();
include 'db.php';

// Check if admin or super admin is logged in
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['Admin', 'Super Admin'])) {
    header("Location: ../index.php");
    exit;
}

// Fetch user details
$userId = $_GET['id'] ?? null;
$role = $_SESSION['user_role'];

$sql = "SELECT pending_changes, update_approved_by FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If no pending changes or already approved by the current role
if (!$user || !$user['pending_changes'] || $user['update_approved_by'] === $role) {
    echo "<p>Nothing to approve or already approved by this role.</p>";
    header("Refresh:2; url=../dashboard/admin.php");
    exit;
}

// Apply pending changes
$pendingChanges = json_decode($user['pending_changes'], true);
$profilePicture = isset($pendingChanges['profile_picture']) ? $pendingChanges['profile_picture'] : null;
$dob = isset($pendingChanges['dob']) && !empty($pendingChanges['dob']) ? $pendingChanges['dob'] : null;

$sql = "UPDATE users 
        SET name = :name, email = :email, mobile = :mobile, address = :address, gender = :gender, dob = :dob, 
            profile_picture = :profile_picture, pending_changes = NULL, update_approved_by = :role 
        WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':name' => $pendingChanges['name'],
    ':email' => $pendingChanges['email'],
    ':mobile' => $pendingChanges['mobile'],
    ':address' => $pendingChanges['address'],
    ':gender' => $pendingChanges['gender'],
    ':dob' => $dob, // Use the validated $dob variable
    ':profile_picture' => $profilePicture,
    ':role' => $role,
    ':id' => $userId
]);

echo "<p>User updates have been approved successfully.</p>";
header("Refresh:2; url=../dashboard/admin.php");
exit;
?>
