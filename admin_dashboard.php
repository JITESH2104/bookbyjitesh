<?php
session_start();
include 'C:/xampp/htdocs/book/database_connection.php'; // Database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Color scheme variables */
        :root {
            --primary-yellow: #FFFACD; /* Light yellow */
            --secondary-yellow: #FFF8DC; /* Cornsilk */
            --highlight-yellow: #FFD700; /* Gold */
            --sidebar-yellow: #FFF4B8; /* Slightly darker yellow for sidebar */
            --white: #FFFFFF;
            --dark-text: #333333;
            --light-gray: #f5f5f5;
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
            line-height: 1.6;
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-yellow);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
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
        
        .nav-menu li {
            margin-bottom: 5px;
        }
        
        .nav-menu li a {
            display: block;
            padding: 12px 20px;
            color: var(--dark-text);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
        }
        
        .nav-menu li a:hover, 
        .nav-menu li a.active {
            background-color: var(--highlight-yellow);
            border-left: 4px solid #FF9800;
        }
        
        .nav-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            flex-grow: 1;
            padding: 20px;
            margin-left:250px;
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
            <li><a href="manage_User_respond.php"><i class="fas fa-envelope"></i> Users Message</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h2>Welcome to Admin Dashboard</h2>
        <p>Use the sidebar to navigate to different sections.</p>
    </div>
</body>
</html>

