<?php
session_start();
include 'C:\\xampp\\htdocs\\book\\database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?redirect=cart");
        exit();
    }
    $book_id = $_POST['book_id'];
    $user_id = intval($_SESSION['user_id']);
    
    // Fetch book details from database
    $book_query = "SELECT title, rent_price, image_path FROM books WHERE id = $book_id";
    $book_result = mysqli_query($conn, $book_query);
    
    if ($book_row = mysqli_fetch_assoc($book_result)) {
        $title = $book_row['title'];
        $price = $book_row['rent_price'];
        $image_path = $book_row['image_path'];
        
        // Check if book is already in cart
        $check_query = "SELECT * FROM cart WHERE book_id = $book_id AND user_id = $user_id";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            $insert_query = "INSERT INTO cart (book_id, user_id, title, price, image_path) VALUES ($book_id, $user_id, '$title', '$price', '$image_path')";
            mysqli_query($conn, $insert_query);
            echo '<div class="notification">Book added to cart!</div>';
        } else {
            echo '<div class="notification">Book is already in the cart!</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookNest - Books for Every Mind, Every Time</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="home2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css"
        integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
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

        /* .menuicon {
            font-size: 25px;
            margin-left: 1vw;
        }

        * Side Menu Styles */ 
        .side-menu {
            position: fixed;
            top: 0;
            left: -300px;
            /* Initially hidden */
            width: 300px;
            height: 100vh;
            background-color: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease-in-out;
            z-index: 1000;
            overflow-y: auto;
        }

        .side-menu.active {
            left: 0;
        }

        .side-menu-header {
            padding: 20px;
            border-bottom: 2px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .close-menu {
            cursor: pointer;
            font-size: 24px;
            color: rgb(112, 78, 33);
        }

        .side-menu-nav {
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .side-menu-nav a {
            padding: 15px 20px;
            color: rgb(255, 145, 0);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background-color 0.3s ease-in-out;
        }

        .side-menu-nav a:hover {
            background-color: rgb(255, 244, 230);
        }

        /* Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        /* Menu Icon */
        .menuicon {
            font-size: 25px;
            cursor: pointer;
            margin-left: 1vw;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-bar form {
            display: flex;
            align-items: center;
            background: #fff;
            border-radius: 50px;
            padding: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease-in-out;
            width: 50%;
        }

        .search-bar form:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        }

        .search-bar input {
            border: none;
            outline: none;
            font-size: 16px;
            padding: 10px 15px;
            flex: 1;
            border-radius: 50px 0 0 50px;
        }

        .search-bar button {
            border: none;
            background: #ff8c00;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 0 50px 50px 0;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: 0.3s ease-in-out;
        }

        .search-bar button:hover {
            background: #ff6b00;
        }

        .search-bar i {
            margin-right: 5px;
        }

        .profile-avatar {
            width: 40px;
            /* Adjust size as needed */
            height: 40px;
            border-radius: 50%;
            /* Makes the image circular */
            object-fit: cover;
            /* Ensures the image is properly cropped */
            border: 2px solid #ff8c00;
            /* Optional: Add a border */
        }

        /* Profile Avatar in Navbar */
        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ff8c00;
            /* Optional border */
        }

        /* Dropdown Profile Info */
        .profile-dropdown {
            width: 250px;
            padding: 10px;
        }

        /* Large Profile Avatar in Dropdown */
        .profile-avatar-large {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 10px;
        }

        /* User Info in Dropdown */
        .profile-info {
            text-align: center;
            padding: 10px;
        }

        .profile-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        .notification-icon {
            position: relative;
            font-size: 24px;
            color: #ff8c00;
            margin-right: 20px;
            text-decoration: none;
        }

        .notification-icon:hover {
            color: #ff6b00;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            font-size: 12px;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 50%;
        }
    </style>
</head>

<body style="background-color: #f7e9de;">

    <header class="header">
        <!-- Search Bar -->
        <i class="ri-menu-line menuicon"></i>

        <div class="search-bar">
            <form method="POST" action="search.php">
                <input type="text" name="search_query" placeholder="🔍 Search for books, authors, or publishers..."
                    required>
                <button type="submit"><i class="ri-search-line"></i> Search</button>
            </form>
        </div>




        <div class="logo">Book<span id="logo-nest">Nest</span></div>

        <nav class="nav">
            <a href="message.php" class="notification-icon">
                <i class="ri-notification-3-line"></i>
                <span class="badge" id="notification-count" style="display: none;">0</span>
            </a>

            <?php
    //session_start();
    include 'C:/xampp/htdocs/book/database_connection.php'; // Ensure this path is correct

    if (!isset($_SESSION['user_id'])) { ?>
            <!-- Show Sign In / Sign Up if not logged in -->
            <div class="dropdown-center">
                <button class="btn dropdown-toggle bg" type="button" data-bs-toggle="dropdown">
                    Sign In / Sign Up
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item bg2" href="login.php">Sign In</a></li>
                </ul>
            </div>
            <?php } else { 
        // Fetch user details from the database
        $user_id = intval($_SESSION['user_id']);
        $query = "SELECT name, last_name, email, mobile_number, profile_photo, address FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // Set default profile image if none exists
        $profile_image = !empty($row['profile.png']) ? $row['profile.png'] : 'profile.png';
        $profile_image_path = "C:/xampp/htdocs/book/profile.png
" . $profile_image;

        // Debugging: Check if the file exists
        if (!file_exists($profile_image_path)) {
            $profile_image = 'profile.png'; // Set default if not found
        }
    ?>
            <!-- Show User Profile Picture and Dropdown with Details -->
            <div class="dropdown-center">
                <button class="btn dropdown-toggle bg" type="button" data-bs-toggle="dropdown">
                    <img src="/book/user/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile"
                        class="profile-avatar">
                </button>
                <ul class="dropdown-menu profile-dropdown">
                    <li class="profile-info">
                        <img src="/book/user/<?php echo htmlspecialchars($profile_image); ?>" alt="Profile"
                            class="profile-avatar-large">
                        <div>
                            <strong>
                                <?php echo htmlspecialchars($row['name'] . ' ' . $row['last_name']); ?>
                            </strong>
                            <p>Email:-
                                <?php echo isset($row['email']) ? htmlspecialchars($row['email']) : 'Not available'; ?>
                            </p>
                            <p>Address:-
                                <?php echo isset($row['address']) ? htmlspecialchars($row['address']) : 'Not available'; ?>
                            </p>

                            <p>Mobile Number:-
                                <?php echo htmlspecialchars($row['mobile_number']); ?>
                            </p>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Profile Picture Upload Form -->
                    <li>

                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item bg2" href="logout.php">Logout</a></li>
                </ul>
            </div>
            <?php } ?>
            <a href="cart.php"><i class="ri-shopping-cart-fill"></i> Cart</a>
        </nav>



    </header>

    <div class="side-menu" id="sideMenu">

        <div class="side-menu-header">
            <div style="font-size: 20px; font-weight:700">Book<span id="logo-nest">Nest</span></div>
            <i class="ri-close-line close-menu"></i>
        </div>

        <nav class="side-menu-nav">
            <a href="homepage.php"><i class="ri-home-line"></i> Home</a>
            <a href="#"><i class="ri-history-line"></i> Order History</a>
            <a href="my_customer_order.php"><i class="ri-layout-horizontal-line"></i>My Customer Order</a>
            <a href="addBook.php"><i class="ri-health-book-line"></i>Add Book</a>
            <a href="myBooks.php"><i class="ri-book-shelf-line"></i>My Book</a>
            <a href="log.php"><i class="ri-logout-box-line"></i> Logout</a>
        </nav>

    </div>


       

    </div>

    <div class="banner">
        <h2 class="banner-text">Great Books. Great Deals.</h2>
        <p class="banner-paragraph">
            Explore a world of endless stories, knowledge, and adventures. Buy or rent your favorite books today.
        </p>
    </div>

    <section class="products">
        <?php
    $query = "SELECT * FROM books WHERE availability_status = 'Available'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($book = mysqli_fetch_assoc($result)) {
            echo '<div class="product">';
            echo '<img src="' . $book['image_path'] . '" alt="Book">';
            echo '<h3>' . $book['title'] . '</h3>';
            echo '<p>By ' . $book['author'] . '</p>';
            echo '<p>Language: ' . $book['language'] . '</p>';
            echo '<p>Price: ₹' . $book['rent_price'] . '</p>';
            echo '<p>Address: ' . $book['address2'] . '</p>';
            
            
            echo '<div class="buttons">';
            echo '<form method="POST" action="rent_details.php">';
            echo '<input type="hidden" name="book_id" value="' . $book['id'] . '">';
            echo '<button type="submit" class="btn btn-primary">Rent Now</button>';
            echo '</form>';
            
            echo '<form method="POST" action="">';
            echo '<input type="hidden" name="book_id" value="' . $book['id'] . '">';
            echo '<button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>';
            echo '</form>';
            echo '</div>';

            echo '</div>';
        }
    } else {
        echo '<p>No books available at the moment.</p>';
    }
    ?>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About BookNest</h3>
                <ul>

                    <li><a href="aboutUs.html">About Us</a></li>
                    <li><a href="contactForm.php">Contact Us</a></li>
                </ul>
            </div>

        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 BookNest. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // function loadNotifications() {
        //     fetch('fetch_notifications.php')
        //         .then(response => response.json())
        //         .then(data => {
        //             let notificationCount = document.getElementById('notification-count');
        //             if (data.count > 0) {
        //                 notificationCount.textContent = data.count;
        //                 notificationCount.style.display = 'inline-block'; // Show badge
        //             } else {
        //                 notificationCount.style.display = 'none'; // Hide if no notifications
        //           }
        //         })
        //         .catch(error => console.error('Error fetching notifications:', error));
        // }

        // // Load notifications every 5 seconds
        // setInterval(loadNotifications, 5000);

        // // Load notifications on page load
        // document.addEventListener("DOMContentLoaded", loadNotifications);

        document.addEventListener("DOMContentLoaded", function () {
            const menuIcon = document.querySelector(".menuicon");
            const sideMenu = document.querySelector(".side-menu");
            const closeMenu = document.querySelector(".close-menu");
            const overlay = document.querySelector(".overlay");

            // Open Sidebar
            menuIcon.addEventListener("click", function () {
                sideMenu.classList.add("active");
                overlay.classList.add("active");
            });

            // Close Sidebar
            closeMenu.addEventListener("click", function () {
                sideMenu.classList.remove("active");
                overlay.classList.remove("active");
            });

            // Close Sidebar when clicking outside
            overlay.addEventListener("click", function () {
                sideMenu.classList.remove("active");
                overlay.classList.remove("active");
            });
        });

    </script>

</body>

</html>