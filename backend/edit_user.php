<?php
session_start();
include 'db.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];

    // Handle optional profile picture upload
    $profilePicture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
            $profilePicture = $fileName;
        }
    }

    try {
        // Prepare the SQL query
        $sql = "UPDATE users SET 
                name = :name, 
                email = :email, 
                role = :role, 
                mobile = :mobile, 
                address = :address, 
                gender = :gender, 
                dob = :dob";

        // Include profile picture if updated
        if ($profilePicture) {
            $sql .= ", profile_picture = :profile_picture";
        }

        $sql .= " WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':mobile' => $mobile,
            ':address' => $address,
            ':gender' => $gender,
            ':dob' => $dob,
            ':id' => $id
        ];

        if ($profilePicture) {
            $params[':profile_picture'] = $profilePicture;
        }

        // Execute the query
        $stmt->execute($params);

        // Send success response
        echo json_encode(['success' => true, 'message' => 'User updated successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
