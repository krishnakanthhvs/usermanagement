<?php
session_start();

// Display message if set
if (isset($_SESSION['message'])) {
    $messageType = $_SESSION['message_type'] === "success" ? "success" : "error";
    echo "<div class='message {$messageType}'>{$_SESSION['message']}</div>";
    unset($_SESSION['message'], $_SESSION['message_type']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="header mb-4">
        <h1>Super Admin Dashboard</h1>
        <div class="user-info">
            <span id="greeting">Hi, <span id="userName">Admin</span></span>
            <button class="logout-btn" id="logout">Logout</button>
        </div>
    </div>
    <button class="add-user-btn" id="addUserBtn">Add User</button> 
    <div class="m-4">
        <div class="controls">
            <div class="left-control">
                <label for="recordsPerPage" class="records-label">Records </label>
                <select id="recordsPerPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                </select>
                <label for="recordsPerPage" class="records-label">page</label>
            </div>
            <div class="right-control">
                <label for="searchInput" class="search-label">Search:</label>
                <input type="text" id="searchInput" placeholder="Search users by name, email, or role">
            </div>
        </div>

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
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" id="closeModal">&times;</span>
            <h2>Add New User</h2>
            <form id="addUserForm">
                <label for="userName">Name:</label>
                <input type="text" id="userName" name="userName" required>

                <label for="userEmail">Email:</label>
                <input type="email" id="userEmail" name="userEmail" required>

                <label for="userRole">Role:</label>
                <select id="userRole" name="userRole" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select>

                <label for="userMobile">Mobile:</label>
                <input type="text" id="userMobile" name="userMobile">

                <label for="userAddress">Address:</label>
                <input type="text" id="userAddress" name="userAddress">

                <label for="userGender">Gender:</label>
                <select id="userGender" name="userGender">
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>

                <label for="userDOB">Date of Birth:</label>
                <input type="date" id="userDOB" name="userDOB">

                <label for="userProfilePicture">Profile Picture:</label>
                <input type="file" id="userProfilePicture" name="userProfilePicture">

                <!-- New Password Field -->
                <label for="userPassword">Password:</label>
                <input type="password" id="userPassword" name="userPassword" required>

                <button type="submit" id="submitAddUser">Add User</button>
            </form>
        </div>
    </div>

    <div id="paginationControls"></div>

    <script>
        $(document).ready(function () {
                    // Show Add User Modal
                    $("#addUserBtn").click(function () {
                        $("#addUserModal").show();
                    });

                    // Close the Add User Modal
                    $("#closeModal").click(function () {
                        $("#addUserModal").hide();
                    });

                    // Close modal if clicked outside of the modal content
                    $(window).click(function (event) {
                        if ($(event.target).is("#addUserModal")) {
                            $("#addUserModal").hide();
                        }
                    });

                    // Handle form submission using AJAX
                    $("#addUserForm").submit(function (e) {
                        e.preventDefault(); // Prevent the default form submission

                        let formData = new FormData(this); // Create FormData object to send file and form data

                        $.ajax({
                            url: "../backend/add_user.php", // Backend PHP script to handle the form submission
                            method: "POST",
                            data: formData,
                            contentType: false, // Let jQuery handle the content type
                            processData: false, // Let jQuery handle the data processing
                            success: function (response) {
                                // Parse response if it's a JSON string
                                let data = JSON.parse(response);

                                if (data.status === 'success') {
                                    alert(data.message); // Success message
                                    $("#addUserModal").hide(); // Hide the modal
                                    window.location.reload(); // Reload page to reflect changes
                                } else {
                                    alert(data.message); // Display error message
                                }
                            },
                            error: function () {
                                alert("There was an error submitting the form.");
                            }
                        });
                    });
                });

                $(document).ready(function () {
            let currentPage = 1;
            let limit = 10;
            let searchQuery = "";

            // Load user data
            function loadUsers(page = 1, limit = 10, query = "") {
                $.ajax({
                    url: "../backend/get_all_users.php",
                    method: "GET",
                    data: { page: page, limit: limit, search: query },
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
                                        ${user.pending_changes ? 
                                            `<a href="../backend/approve_user.php?id=${user.id}">Approve Changes</a>` :
                                            (user.approved == 0 ? 
                                                `<a href="../backend/approve_user.php?id=${user.id}">Approve User</a>` : 
                                                "<span>Approved</span>"
                                            )
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
                loadUsers(currentPage, limit, searchQuery);
            });

            // Next page
            $("#nextPage").click(function () {
                if (currentPage < Math.ceil(totalUsers / limit)) {
                    currentPage++;
                    loadUsers(currentPage, limit, searchQuery);
                }
            });

            // Previous page
            $("#prevPage").click(function () {
                if (currentPage > 1) {
                    currentPage--;
                    loadUsers(currentPage, limit, searchQuery);
                }
            });

            // Change records per page
            $("#recordsPerPage").change(function () {
                limit = $(this).val();
                loadUsers(currentPage, limit, searchQuery);
            });

            // Search functionality
            $("#searchInput").on("keyup", function () {
                searchQuery = $(this).val();
                loadUsers(currentPage, limit, searchQuery);
            });

            // Logout functionality
            $("#logout").click(function () {
                $.ajax({
                    url: "../backend/logout.php",
                    method: "POST",
                    success: function () {
                        alert("Logged out successfully.");
                        window.location.href = "../index.php"; // Redirect to login page
                    },
                    error: function () {
                        alert("Failed to logout.");
                    }
                });
            });

            // Edit User functionality
            $(document).on("click", ".editUser", function (e) {
                e.preventDefault(); // Prevent default anchor action
                let userId = $(this).data("id");
                window.location.href = `../backend/edit_user.php?id=${userId}`; // Redirect to edit page
            });
        });
    </script>
</body>
</html>
