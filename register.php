<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <form action="backend/user_management.php" method="POST" enctype="multipart/form-data">
        <label>Name:</label><br>
        <input type="text" name="name" required><br>
        <label>Role:</label><br>
        <select name="role" id="role">
            <option value="User">User</option>
            <option value="Admin">Admin</option>
            <option value="Super Admin">Super Admin</option>
        </select><br>
        <label>Mobile:</label><br>
        <input type="text" name="mobile" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Address:</label><br>
        <textarea name="address"></textarea><br>
        <label>Gender:</label><br>
        <input type="radio" name="gender" value="Male" required> Male
        <input type="radio" name="gender" value="Female" required> Female
        <input type="radio" name="gender" value="Other" required> Other<br>
        <label>Date of Birth:</label><br>
        <input type="date" name="date_of_birth" required><br>
        <label>Profile Picture:</label><br>
        <input type="file" name="profile_picture" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
