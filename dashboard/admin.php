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
    <link rel="stylesheet" href="/css/style.css">

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

    <script>
        $(document).ready(function () {
            let currentPage = 1;
            let limit = 10;

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
        });
    </script>
</body>
</html>
