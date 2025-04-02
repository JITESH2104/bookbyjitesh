<?php
session_start();
include 'C:/xampp/htdocs/book/database_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Books</title>
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

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .search-bar input {
            flex: 1;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 15px;
            border: none;
            background-color: var(--highlight-yellow);
            color: var(--dark-text);
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-bar button:hover {
            background-color: #FF9800;
            color: var(--white);
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
        .book-cover {
            width: 50px;
            height: 50px;
            object-fit: cover;
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
            <li><a class="active" href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a></li>
            <li><a href="manage_rentals.php"><i class="fas fa-shopping-cart"></i> Manage Rentals</a></li>
            <li><a href="manage_User_respond.php"><i class="fas fa-envelope"></i> Users Message</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Manage Books</h2>
        
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by Title, Author, or Genre" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <?php
        $searchQuery = "";
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $conn->real_escape_string($_GET['search']);
            $searchQuery = " WHERE title LIKE '%$search%' OR author LIKE '%$search%' OR genre LIKE '%$search%'";
        }

        $result = $conn->query("SELECT id, title, author, genre, rent_price, image_path FROM books $searchQuery");
        
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>Price</th>
                        
                        <th>Actions</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['author']}</td>
                        <td>{$row['genre']}</td>
                        <td>{$row['rent_price']}</td>
                      
                        <td class='actions'>
                            <a href='edit_book.php?id={$row['id']}'>Edit</a> |
                            <a href='delete_book.php?id={$row['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No books found.</p>";
        }
        ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>