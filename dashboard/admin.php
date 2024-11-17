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
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>
    <button class="logout-btn" id="logout">Logout</button>
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

    <script>
        $(document).ready(function () {
            // Fetch user data using AJAX
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
                                    <a href="../backend/approve_user.php?id=${user.id}">Approve</a>
                                    <a href="../backend/delete_user.php?id=${user.id}">Delete</a>
                                </td>
                            </tr>
                        `;
                    });

                    $("#userTable tbody").html(rows);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching user data:", error);
                }
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
