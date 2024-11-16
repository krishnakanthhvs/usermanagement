<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Super Admin') {
    header("Location: ../index.php");
    exit;
}
include '../backend/db.php';

// Fetch all users
$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Super Admin Dashboard</title>
</head>
<body>
    <h1>Super Admin Dashboard</h1>
    <a href="../logout.php" style="float: right;">Logout</a>
<hr>

    <!-- Add New User Form -->
    <h2>Add New User</h2>
    <form action="../backend/add_user.php" method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Role:</label><br>
        <select name="role" required>
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select><br>
        <label>Mobile:</label><br>
        <input type="text" name="mobile" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Profile Picture:</label><br>
        <input type="file" name="profile_picture" required><br>
        <button type="submit">Add User</button>
    </form>

    <!-- Display User Table -->
    <h2>All Users</h2>
    <table border="1">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
            <td>
                <a href="../backend/approve_user.php?id=<?= $user['id'] ?>">Approve</a>
                <a href="../backend/delete_user.php?id=<?= $user['id'] ?>">Delete</a>
                <a href="../backend/edit_user.php?id=<?= $user['id'] ?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
