<?php
session_start();

// Display message if set
if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message_type'] === "success" ? "success" : "error";
    echo "<div class='message {$messageType}'>{$_SESSION['message']}</div>";
    unset($_SESSION['message'], $_SESSION['message_type']); // Clear message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .logout-btn {
            float: right;
            margin-bottom: 10px;
        }

        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        #paginationControls {
            margin-top: 20px;
            text-align: center;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
        }

        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
        }

        .modal input, .modal select, .modal button {
            width: 100%;
            margin-bottom: 10px;
        }

        .modal button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <button class="logout-btn" id="logout">Logout</button>
    
    <label for="recordsPerPage">Records per page: </label>
    <select id="recordsPerPage">
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
    </select>

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

    <div id="paginationControls"></div>

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
        <button id="closeEditModal">Close</button>
    </div>

    <script>
        $(document).ready(function () {
            let currentPage = 1;
            let limit = 10;

            // Load user data
            function loadUsers(page = 1, limit = 10) {
                $.ajax({
                    url: "../backend/get_all_users.php",
                    method: "GET",
                    data: { page: page, limit: limit },
                    dataType: "json",
                    success: function (data) {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        let rows = "";
                        data.users.forEach(user => {
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
                                        ${user.profile_picture ? `<img src="../uploads/${user.profile_picture}" alt="Profile" width="50">` : "No Picture"}
                                    </td>
                                    <td>
                                        <a href="#" class="editUser" data-id="${user.id}">Edit</a> |
                                        ${user.approved != 1 ? 
                                            `<a href="../backend/approve_user.php?id=${user.id}">Approve</a>` : 
                                            "<span>Approved</span>"
                                        }
                                        <a href="../backend/delete_user.php?id=${user.id}">Delete</a>
                                    </td>
                                </tr>
                            `;
                        });

                        $("#userTable tbody").html(rows);

                        // Generate pagination controls
                        const totalPages = Math.ceil(data.total_users / limit);
                        let paginationControls = `<button ${page === 1 ? 'disabled' : ''} id="prevPage">Previous</button>`;
                        for (let i = 1; i <= totalPages; i++) {
                            paginationControls += `<button class="pageNumber" data-page="${i}" ${i === page ? 'disabled' : ''}>${i}</button>`;
                        }
                        paginationControls += `<button ${page === totalPages ? 'disabled' : ''} id="nextPage">Next</button>`;

                        $("#paginationControls").html(paginationControls);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching user data:", error);
                    }
                });
            }

            loadUsers(currentPage, limit);

            // Pagination button click
            $(document).on("click", ".pageNumber", function () {
                currentPage = $(this).data("page");
                loadUsers(currentPage, limit);
            });

            // Next page
            $("#nextPage").click(function () {
                if (currentPage < Math.ceil(totalUsers / limit)) {
                    currentPage++;
                    loadUsers(currentPage, limit);
                }
            });

            // Previous page
            $("#prevPage").click(function () {
                if (currentPage > 1) {
                    currentPage--;
                    loadUsers(currentPage, limit);
                }
            });

            // Change records per page
            $("#recordsPerPage").change(function () {
                limit = $(this).val();
                loadUsers(currentPage, limit);
            });

            // Edit user functionality
            $(document).on("click", ".editUser", function (e) {
                e.preventDefault();
                const userId = $(this).data("id");

                // Fetch user data for editing
                $.ajax({
                    url: `../backend/get_user.php?id=${userId}`,
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

                        // Show the edit modal
                        $(".modal-overlay, #editModal").fadeIn();
                    },
                    error: function () {
                        alert("Error fetching user details.");
                    }
                });
            });

            // Handle form submission for editing user
            $("#editUserForm").submit(function (e) {
                e.preventDefault();

                const formData = new FormData(this);
                $.ajax({
                    url: "../backend/edit_user.php",  // Backend script for editing users
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        alert("User updated successfully.");
                        $(".modal-overlay, #editModal").fadeOut();
                        loadUsers(currentPage, limit);  // Reload the users after the update
                    },
                    error: function () {
                        alert("Failed to update user.");
                    }
                });
            });

            // Close the edit modal
            $("#closeEditModal").click(function () {
                $(".modal-overlay, #editModal").fadeOut();
            });

            // Logout
            $("#logout").click(function () {
                window.location.href = "../backend/logout.php";
            });
        });
    </script>
</body>
</html>
