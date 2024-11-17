<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="topnav">
        <h2>Super Admin Dashboard</h2>
        <div class="user-info">
            <img src="../uploads/default-profile.png" alt="Profile Picture" id="profilePicture">
            <span id="loggedInUser">John Doe</span>
            <button id="logoutButton">Logout</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <table id="userTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Profile Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic Data Will Be Rendered Here -->
            </tbody>
        </table>
    </div>

    <!-- Modal for Editing User -->
    <div class="modal-overlay"></div>
    <div class="modal" id="editModal">
        <h2>Edit User</h2>
        <form id="editUserForm">
            <input type="hidden" name="id" id="editUserId">
            <label>Name:</label><br>
            <input type="text" name="name" id="editName" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" id="editEmail" required><br>
            <label>Role:</label><br>
            <select name="role" id="editRole" required>
                <option value="User">User</option>
                <option value="Admin">Admin</option>
            </select><br>
            <label>Mobile:</label><br>
            <input type="text" name="mobile" id="editMobile" required><br>
            <label>Address:</label><br>
            <input type="text" name="address" id="editAddress" required><br>
            <label>Gender:</label><br>
            <select name="gender" id="editGender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select><br>
            <label>Date of Birth:</label><br>
            <input type="date" name="dob" id="editDob"><br>
            <label>Profile Picture:</label><br>
            <input type="file" name="profile_picture" id="editProfilePicture"><br>
            <button type="submit">Save Changes</button>
        </form>
        <button id="closeModal">Close</button>
    </div>

    <script>
    $(document).ready(function () {
        // Fetch user data and populate the table
        function loadUsers() {
            $.ajax({
                url: "../backend/get_all_users.php",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    let rows = "";
                    data.forEach(user => {
                        let approveButton = user.approved === 1
                            ? ""
                            : `<a href="../backend/approve_user.php?id=${user.id}">Approve</a>`;
                        rows += `
                            <tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.role}</td>
                                <td>${user.mobile}</td>
                                <td>${user.address}</td>
                                <td>${user.gender}</td>
                                <td>${user.dob || "N/A"}</td>
                                <td>
                                    ${user.profile_picture 
                                        ? `<img src="../uploads/${user.profile_picture}" alt="Profile" width="50">` 
                                        : "No Picture"}
                                </td>
                                <td>
                                    <a href="#" class="editUser" data-id="${user.id}">Edit</a> |
                                    ${approveButton} |
                                    <a href="../backend/delete_user.php?id=${user.id}">Delete</a>
                                </td>
                            </tr>`;
                    });

                    $("#userTable tbody").html(rows);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching user data:", error);
                    alert("Failed to load users. Please try again.");
                }
            });
        }

        loadUsers();

        // Show modal with user data for editing
        $(document).on("click", ".editUser", function (e) {
            e.preventDefault();
            const userId = $(this).data("id");

            $.ajax({
                url: `../backend/get_user.php?id=${userId}`, // Corrected URL syntax
                method: "GET",
                dataType: "json",
                success: function (user) {
                    if (user.error) {
                        alert(user.error);
                        return;
                    }

                    // Populate the modal with the user data
                    $("#editUserId").val(user.id);
                    $("#editName").val(user.name);
                    $("#editEmail").val(user.email);
                    $("#editRole").val(user.role);
                    $("#editMobile").val(user.mobile);
                    $("#editAddress").val(user.address);
                    $("#editGender").val(user.gender);
                    $("#editDob").val(user.dob);
                    $("#editProfilePicture").val(null); // Reset the file input

                    // Show the modal
                    $(".modal-overlay, #editModal").fadeIn();
                },
                error: function () {
                    alert("Error fetching user details.");
                }
            });
        });

        // Close modal
        $("#closeModal").click(function () {
            $(".modal-overlay, #editModal").fadeOut();
        });

        // Handle form submission for editing user
        $("#editUserForm").submit(function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            $.ajax({
                url: "../backend/edit_user.php",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    alert("User updated successfully.");
                    $(".modal-overlay, #editModal").fadeOut();
                    loadUsers();
                },
                error: function () {
                    alert("Failed to update user.");
                }
            });
        });

        // Logout functionality
        $("#logoutButton").click(function () {
            $.ajax({
                url: "../backend/logout.php",
                method: "GET",
                success: function () {
                    window.location.href = "../index.php";
                },
                error: function () {
                    alert("Error logging out.");
                }
            });
        });
    });
</script>

</body>
</html>
