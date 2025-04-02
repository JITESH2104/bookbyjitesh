<?php
session_start();
include 'C:/xampp/htdocs/book/database_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-yellow: #FFFACD;
            --secondary-yellow: #FFF8DC;
            --highlight-yellow: #FFD700;
            --sidebar-yellow: #FFF4B8;
            --white: #FFFFFF;
            --dark-text: #333333;
            --border-color: #E6E6E6;
            --sidebar-width: 250px;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--white);
            color: var(--dark-text);
            display: flex;
        }
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-yellow);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding-top: 20px;
        }
        .sidebar-title {
            text-align: center;
            padding: 20px;
            background-color: var(--highlight-yellow);
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid var(--border-color);
        }
        .nav-menu {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        .nav-menu li a {
            display: block;
            padding: 12px 20px;
            color: var(--dark-text);
            text-decoration: none;
            border-left: 4px solid transparent;
        }
        .nav-menu li a:hover, .nav-menu li a.active {
            background-color: var(--highlight-yellow);
            border-left: 4px solid #FF9800;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: var(--sidebar-width);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid var(--border-color);
            text-align: left;
        }
        th {
            background-color: var(--highlight-yellow);
            color: var(--dark-text);
        }
        .profile-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-input {
            padding: 8px;
            width: 300px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        .search-button {
            padding: 8px 16px;
            background-color: var(--highlight-yellow);
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-title">
            <i class="fas fa-users"></i> Manage Users
        </div>
        <ul class="nav-menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="manage_user.php" class="active"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a></li>
            <li><a href="manage_rentals.php"><i class="fas fa-shopping-cart"></i> Manage Rentals</a></li>
            <li><a href="manage_User_respond.php"><i class="fas fa-envelope"></i> Users Message</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Users</h2>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by ID, Name, Email, or Mobile Number" class="search-input">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        $searchQuery = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $searchQuery = " WHERE id LIKE '%$search%' OR name LIKE '%$search%' OR email LIKE '%$search%' OR mobile_number LIKE '%$search%'";
        }

        $result = $conn->query("SELECT id, name, last_name, email, mobile_number, address, profile_photo, role FROM users $searchQuery");
        
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile Number</th>
                        <th>Address</th>
                       
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']} {$row['last_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['mobile_number']}</td>
                        <td>{$row['address']}</td>
                                              <td>{$row['role']}</td>
                        <td>
                            <a href='edit_user.php?id={$row['id']}'>Edit</a> |
                            <a href='delete_user.php?id={$row['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>