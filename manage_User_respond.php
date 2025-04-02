<?php
session_start();
include 'C:\xampp\htdocs\book\database_connection.php'; // Database connection

// Delete message if delete request is received
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $delete_query = "DELETE FROM admin_message WHERE user_id = '$id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Message deleted successfully!'); window.location.href='manage_User_respond.php';</script>";
    } else {
        echo "<script>alert('Error deleting message.'); window.location.href='manage_User_respond.php';</script>";
    }
}

// Fetch messages from the database
$query = "SELECT id, name, email, message, message_date FROM admin_message ORDER BY message_date DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage User Responses</title>
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
            min-height: 100vh;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-yellow);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }

        .sidebar-title {
            padding: 20px;
            background-color: var(--highlight-yellow);
            text-align: center;
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
        table, th, td {
            border: 1px solid var(--border-color);
            text-align: left;
        }
        th, td {
            padding: 12px;
        }
        th {
            background-color: var(--highlight-yellow);
            color: var(--dark-text);
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: blue;
        }
        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-title">
            <i class="fas fa-tachometer-alt"></i> Admin Dashboard
        </div>
        <ul class="nav-menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="manage_user.php"><i class="fas fa-users"></i> Manage Users</a></li>
            <li><a href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a></li>
            <li><a href="manage_rentals.php"><i class="fas fa-shopping-cart"></i> Manage Rentals</a></li>
            <li><a class="active" href="manage_User_respond.php"><i class="fas fa-envelope"></i> Users Message</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>User Messages</h2>
        
        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Message Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['message_date']; ?></td>
                    <td>
                        <a href="manage_User_respond.php?id=<?php echo $row['user_id']; ?>" onclick="return confirm('Are you sure?')" style="color:red;">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
