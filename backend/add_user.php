<?php
session_start();

// Include database connection
include 'db.php'; 

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['userName'];
    $email = $_POST['userEmail'];
    $role = $_POST['userRole'];
    $mobile = $_POST['userMobile'];
    $address = $_POST['userAddress'];
    $gender = $_POST['userGender'];
    $dob = $_POST['userDOB'];
    $password = $_POST['userPassword']; 
    $profilePicture = $_FILES['userProfilePicture'];

    // Hash the password for secure storage
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload (if any)
    if ($profilePicture['error'] === 0) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($profilePicture['name']);
        if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
            $profilePicturePath = basename($profilePicture['name']);
        } else {
            $profilePicturePath = null; 
        }
    } else {
        $profilePicturePath = null; 
    }

    try {
        $query = "INSERT INTO users (name, email, role, mobile, address, gender, dob, password, profile_picture) 
                  VALUES (:name, :email, :role, :mobile, :address, :gender, :dob, :password, :profile_picture)";
        
        // Prepare and bind parameters
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':password', $hashedPassword); 
        $stmt->bindParam(':profile_picture', $profilePicturePath);

        // Execute the query
        $stmt->execute();

        echo json_encode(array("status" => "success", "message" => "User added successfully!"));
    } catch (PDOException $e) {
        echo json_encode(array("status" => "error", "message" => "Failed to add user: " . $e->getMessage()));
    }
}
?>
