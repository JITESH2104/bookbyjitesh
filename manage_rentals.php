<?php
session_start();
include 'C:/xampp/htdocs/book/database_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rentals</title>
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
            color: var(--dark-text);
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
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
            transition: all 0.3s;
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
            background-color: var(--secondary-yellow);
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }
        th, td {
            padding: 12px;
        }
        th {
            background-color: var(--highlight-yellow);
            color: var(--dark-text);
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-input {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-button {
            padding: 8px 16px;
            background-color: var(--highlight-yellow);
            color: var(--dark-text);
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
            <li><a href="manage_rentals.php" class="active"><i class="fas fa-shopping-cart"></i> Manage Rentals</a></li>
            <li><a href="manage_User_respond.php"><i class="fas fa-envelope"></i> Users Message</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Rentals</h2>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by Rental ID, User, or Book Title" class="search-input">
            <button type="submit" class="search-button">Search</button>
        </form>

        <?php
        $searchQuery = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $searchQuery = " WHERE books.id LIKE '%$search%' OR users.name LIKE '%$search%' OR books.title LIKE '%$search%'";
        }
        
        $result = $conn->query("SELECT rentals.id AS rental_id, users.name AS user_name, books.title AS book_title, 
                                        rentals.rental_date, rentals.rental_days, rentals.return_date
                                FROM rentals 
                                JOIN books ON rentals.id = books.id 
                                JOIN users ON rentals.renter_id = users.id 
                                $searchQuery");
        

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Rental ID</th>
                        <th>User Name</th>
                        <th>Book Title</th>
                        <th>Rent Date</th>
                        <th>Return Date</th>
                        <th>Actions</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['rental_id']}</td>
                        <td>{$row['user_name']}</td>
                        <td>{$row['book_title']}</td>
                        <td>{$row['rental_date']}</td>
                        <td>{$row['return_date']}</td>
                        <td>
                            <a href='edit_rental.php?rental_id={$row['rental_id']}'><i class='fas fa-edit'></i> Edit</a> |
                            <a href='delete_rental.php?rental_id={$row['rental_id']}' style='color: red;'><i class='fas fa-trash'></i> Delete</a>
                        </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No rentals found.</p>";
        }
        ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>