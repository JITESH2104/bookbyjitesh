<?php
session_start(); // Ensure session starts at the top

include 'C:\\xampp\\htdocs\\book\\database_connection.php'; // Ensure correct path

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest - Books for Every Mind, Every Time</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="home2.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .bg2:hover {
            background-color: transparent;
        }
        .bg {
            background-color: rgb(255, 255, 255);
            color: rgb(112, 78, 33);
            border-radius: 20px;
            border: none;
        }
        .bg:hover {
            background-color: rgb(255, 239, 215);
            color: rgb(112, 78, 33);
            border-radius: 20px;
        }
        .dropdown-menu {
            background-color: rgb(255, 244, 230);
        }
        .notification {
            position: fixed;
            bottom: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0.9;
        }
        .result-item {
            background-color: #fff;
            padding: 15px;
            margin: 10px 0;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .result-item img {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .result-item h4 {
            margin: 10px 0;
            color: #5a4033;
        }
        .result-item p {
            margin: 5px 0;
            color: #6c757d;
        }
        .btn {
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body style="background-color: #f7e9de;">

<header class="header">
    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search_query" placeholder="Search for books, authors, or publishers..." required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="logo">Book<span id="logo-nest">Nest</span></div>
    <nav class="nav">
        <div class="dropdown-center">
            <button class="btn dropdown-toggle bg" type="button" data-bs-toggle="dropdown">
                Sign In / Sign Up
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item bg2" href="login.php">Sign In</a></li>
            </ul>
        </div>
        <a href="cart.php"><i class="ri-shopping-cart-fill"></i> Cart</a>
    </nav>
</header>

    
    
</div>

<div class="banner">
    <h2 class="banner-text">Great Books. Great Deals.</h2>
    <p class="banner-paragraph">
        Explore a world of endless stories, knowledge, and adventures. Buy or rent your favorite books today.
    </p>
</div>

<section class="products">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
        $search_query = mysqli_real_escape_string($conn, $_POST['search_query']);

        // Query to search books by title, author, or publisher
        $query = "SELECT * FROM books WHERE title LIKE '%$search_query%' OR author LIKE '%$search_query%' OR book_publisher LIKE '%$search_query%'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($book = mysqli_fetch_assoc($result)) {
                echo '<div class="result-item">';
                echo '<img src="' . $book['image_path'] . '" alt="Book">';
                echo '<h4>' . $book['title'] . '</h4>';
                echo '<p>By ' . $book['author'] . '</p>';
                echo '<p>Publisher: ' . $book['book_publisher'] . '</p>';
                echo '<p>Price: ₹' . $book['rent_price'] . '</p>';

                // Check availability status
                if ($book['availability_status'] === 'rented') {
                    echo '<form method="POST" action="availability_form.php">';
                    echo '<input type="hidden" name="book_id" value="' . $book['id'] . '">';
                    echo '<button type="submit" class="btn btn-warning">Check Availability</button>';
                    echo '</form>';
                } else {
                    echo '<form method="POST" action="rent_details.php.php">';
                    echo '<input type="hidden" name="book_id" value="' . $book['id'] . '">';
                    echo '<button type="submit" class="btn btn-success">Rent Now</button>';
                    echo '</form>';
                }

                echo '</div>';
            }
        } else {
            echo '<p>No books found.</p>';
        }
    } else {
        echo '<p>Please enter a search query.</p>';
    }
    ?>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About BookNest</h3>
            <ul>
                <li><a href="#">Returns & Refunds</a></li>
                <li><a href="aboutUs.html">About Us</a></li>
                <li><a href="contactForm.html">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Connect With Us</h3>
            <p>Follow us on social media for updates and bookish content!</p>
            <div class="social-links">
                <a href="#"><i class="ri-facebook-fill"></i></a>
                <a href="#"><i class="ri-twitter-fill"></i></a>
                <a href="#"><i class="ri-instagram-line"></i></a>
                <a href="#"><i class="ri-pinterest-fill"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 BookNest. All Rights Reserved.</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notifications = document.querySelectorAll('.notification');
        notifications.forEach(notification => {
            setTimeout(() => notification.remove(), 3000);
        });
    });
</script>
</body>
</html>
