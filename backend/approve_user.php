<?php
session_start();
include '../backend/db.php'; // Include DB connection

// Check if the user is logged in as Admin or Super Admin
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'Admin' && $_SESSION['user_role'] !== 'Super Admin')) {
    header("Location: ../index.php");
    exit;
}

// Get the user ID from the URL
$userId = isset($_GET['id']) ? $_GET['id'] : null;

if ($userId) {
    // Get user details including pending changes and current approval status
    $sql = "SELECT pending_changes, approved FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if the user is approved or not
        if ($user['approved'] == 1) {
            // The user is approved, check for pending changes
            if ($user['pending_changes']) {
                // Apply pending changes if they exist
                $pendingChanges = json_decode($user['pending_changes'], true);

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

                $_SESSION['message'] = "User's profile changes have been approved.";
                $_SESSION['message_type'] = "success";
                
                // Redirect based on role (Admin or Super Admin)
                if ($_SESSION['user_role'] === 'Admin') {
                    header("Location: ../dashboard/admin.php");
                } else {
                    header("Location: ../dashboard/superadmin.php");
                }
                exit;
            } else {
                // No pending changes, just notify that the user is already approved
                $_SESSION['message'] = "User is already approved and there are no pending changes.";
                $_SESSION['message_type'] = "info";
                
                // Redirect based on role (Admin or Super Admin)
                if ($_SESSION['user_role'] === 'Admin') {
                    header("Location: ../dashboard/admin.php");
                } else {
                    header("Location: ../dashboard/superadmin.php");
                }
                exit;
            }
        } else {
            // The user is not approved, and only an Admin or Super Admin can approve them
            if ($_SESSION['user_role'] === 'Admin' || $_SESSION['user_role'] === 'Super Admin') {
                // Approve the user explicitly
                $sqlUpdateApproval = "UPDATE users SET approved = 1 WHERE id = :id";
                $stmtUpdateApproval = $pdo->prepare($sqlUpdateApproval);
                $stmtUpdateApproval->execute([':id' => $userId]);

                $_SESSION['message'] = "User has been approved successfully.";
                $_SESSION['message_type'] = "success";
                
                // Redirect based on role (Admin or Super Admin)
                if ($_SESSION['user_role'] === 'Admin') {
                    header("Location: ../dashboard/admin.php");
                } else {
                    header("Location: ../dashboard/superadmin.php");
                }
                exit;
            } else {
                // If the logged-in user is not an Admin or Super Admin
                $_SESSION['message'] = "You do not have permission to approve this user.";
                $_SESSION['message_type'] = "error";
                header("Location: ../dashboard/superadmin.php");
                exit;
            }
        }
    } else {
        // User not found
        $_SESSION['message'] = "User not found.";
        $_SESSION['message_type'] = "error";
        header("Location: ../dashboard/superadmin.php");
        exit;
    }
}
?>