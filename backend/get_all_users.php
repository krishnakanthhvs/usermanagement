<?php
// Include the necessary files
include 'db.php'; // Database connection

// Retrieve GET parameters
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Current page, default to 1
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Limit per page, default to 10
$search = isset($_GET['search']) ? trim($_GET['search']) : ""; // Search term
$offset = ($page - 1) * $limit; // Calculate offset

try {
    // Build the search condition for the query if search term is provided
    $searchCondition = $search ? "WHERE name LIKE :search OR email LIKE :search OR role LIKE :search" : "";

    // Query to fetch users with their approved status and any pending changes
    $sql = "SELECT id, name, email, role, mobile, address, gender, dob, profile_picture, approved, pending_changes
            FROM users 
            $searchCondition 
            LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    // Bind parameters for search if applicable
    if ($search) {
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Query to get the total number of users for pagination
    $countSql = "SELECT COUNT(*) FROM users $searchCondition";
    $countStmt = $pdo->prepare($countSql);

    // Bind search parameter to count query if applicable
    if ($search) {
        $countStmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
    }
    $countStmt->execute();
    $totalUsers = $countStmt->fetchColumn();

    // Prepare the response with the list of users and total user count
    $response = [
        'users' => $users,
        'total_users' => $totalUsers,
    ];

    // Return the JSON response
    echo json_encode($response);
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
