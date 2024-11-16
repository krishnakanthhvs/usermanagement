<?php
include '../backend/db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <form action="../backend/update_user.php" method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <label>Name:</label><br>
        <input type="text" name="name" value="<?= $user['name'] ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
        <label>Role:</label><br>
        <select name="role" required>
            <option value="User" <?= $user['role'] === 'User' ? 'selected' : '' ?>>User</option>
            <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
        </select><br>
        <label>Mobile:</label><br>
        <input type="text" name="mobile" value="<?= $user['mobile'] ?>" required><br>
        <button type="submit">Update User</button>
    </form>
</body>
</html>
