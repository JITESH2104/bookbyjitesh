<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Rental - BookNest</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #131921;
            --secondary-color: #232f3e;
            --accent-color: #f0c14b;
            --light-bg: #f8f8f8;
            --border-color: #ddd;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 0;
        }
        
        .topnav {
            background-color: var(--primary-color);
            padding: 10px 20px;
            color: white;
        }
        
        .search-container {
            background-color: var(--secondary-color);
            padding: 8px 20px;
        }
        
        .search-bar {
            border-radius: 4px;
            overflow: hidden;
        }
        
        .search-input {
            border: none;
            height: 40px;
            border-radius: 4px 0 0 4px;
        }
        
        .search-button {
            background-color: var(--accent-color);
            border: none;
            height: 40px;
            width: 45px;
            border-radius: 0 4px 4px 0;
        }
        
        .book-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
            padding: 20px;
        }
        
        .book-image-container {
            border: 1px solid var(--border-color);
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .book-image {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        
        .book-title {
            font-size: 24px;
            margin-bottom: 5px;
            color: #0F1111;
        }
        
        .book-author {
            color: #007185;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .rating-stars {
            color: #FFA41C;
            margin-bottom: 15px;
        }
        
        .book-price {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .rental-period {
            background-color: #eaeded;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        
        .book-details {
            margin-top: 20px;
            border-top: 1px solid var(--border-color);
            padding-top: 20px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 8px;
        }
        
        .detail-label {
            flex: 0 0 120px;
            font-weight: bold;
        }
        
        .detail-value {
            flex: 1;
        }
        
        .rent-button {
            background-color: var(--accent-color);
            border: 1px solid #a88734;
            border-radius: 4px;
            padding: 8px 15px;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .rent-button:hover {
            background-color: #e9b72b;
        }
        
        .buy-options {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 15px;
        }
        
        .delivery-info {
            font-size: 14px;
            margin: 10px 0;
        }
        
        .in-stock {
            color: #007600;
            font-weight: bold;
        }
        
        .footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Top Navigation -->
    <div class="topnav">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-2">
                    <h4 class="m-0">BookNest</h4>
                </div>
                <div class="col-md-6">
                    <div class="input-group search-bar">
                        <input type="text" class="form-control search-input" placeholder="Search books...">
                        <button class="search-button" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    
                    <span class="me-3"><i class="fas fa-heart me-1"></i> Wishlist</span>
                    <span><i class="fas fa-shopping-cart me-1"></i> Cart</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Categories -->
    <div class="search-container">
        <div class="container-fluid">
         

        </div>
    </div>
    
    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none;">Home</a></li>
                <li class="breadcrumb-item"><a href="#" style="text-decoration: none;">Books</a></li>
                <li class="breadcrumb-item active" aria-current="page">Book Details</li>
            </ol>
        </nav>
        
        <?php
        if (session_status() == PHP_SESSION_NONE) {
            
        }

        include 'C:\\xampp\\htdocs\\book\\database_connection.php';  // Database connection file

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
            $book_id = intval($_POST['book_id']);
            
            $query = "SELECT * FROM books WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($book = $result->fetch_assoc()) {
        ?>
        <div class="row">
            <!-- Book Image Column -->
            <div class="col-md-4">
                <div class="book-container text-center">
                    <div class="book-image-container">
                        <img src="<?php echo htmlspecialchars($book['image_path']); ?>" alt="Book Cover" class="book-image">
                    </div>
                    <div class="text-center">
                        <button class="btn btn-sm btn-light">
                            <i class="fas fa-expand-alt"></i> Enlarge
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Book Details Column -->
            <div class="col-md-5">
                <div class="book-container">
                    <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>
                    <p class="book-author">by <?php echo htmlspecialchars($book['author']); ?></p>
                    
                 
                    
                    <hr>
                    
                    <div class="book-price">
                        <span class="text-muted">Rent Price:</span> 
                        <span class="text-danger fw-bold">₹<?php echo htmlspecialchars($book['rent_price']); ?></span> 
                        <span class="text-muted">per day</span>
                    </div>
                    
                    
                    
                    <div class="book-details">
                        <h5>About this book</h5>
                        <p><?php echo htmlspecialchars($book['description']); ?></p>
                        
                        <h6 class="mt-4">Book Details</h6>
                        <div class="detail-row">
                            <div class="detail-label">ISBN 10:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($book['isbn_10']); ?></div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">ISBN 13:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($book['isbn_13']); ?></div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Genre:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($book['genre']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Language:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($book['language']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Pages:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($book['pages']); ?></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Condition:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($book['book_condition']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rent Options Column -->
            <div class="col-md-3">
               
                   
                    
                    <p class="in-stock">
                        <i class="fas fa-check-circle"></i> In Stock
                    </p>
                    
                  
                    
                    <form method="POST" action="confirm_rent.php">
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        <button type="submit" class="rent-button">Rent Now</button>
                    </form>
                    
               
                    <hr>
                    
                    
                    
                    <div class="sold-by small mt-2">
                        <span class="text-muted">Sold and fulfilled by</span> 
                        <span class="text-info">BookBazaar</span>
                    </div>
                </div>
                
               
            </div>
        </div>
        
        <!-- Similar Books Section -->
        <div class="book-container mt-4">
            <h5 class="mb-3">Customers who rented this book also rented</h5>
            <div class="row">
                
               
            </div>
        </div>
        
        <!-- Customer Reviews -->
        <div class="book-container mt-4">
            <h5>Customer Reviews</h5>
            <div class="row align-items-center mb-4">
                <div class="col-md-3 text-center">
                    
                    
                    
                </div>
                
            </div>
            
            <button class="btn btn-outline-secondary">Write a Review</button>
            
            <hr>
            
            <div class="review mb-4">
                <div class="d-flex align-items-center mb-2">
                    
                    <span class="fw-bold">Must read!</span>
                </div>
                 
            </div>
            
           
            
            <div class="text-center mt-4">
                <button class="btn btn-outline-secondary btn-sm">See all reviews</button>
            </div>
        </div>
        
        <?php
            } else {
                echo '<div class="alert alert-warning text-center">Book not found!</div>';
            }

            $stmt->close();
        } else {
            echo '<div class="alert alert-danger text-center">Invalid request!</div>';
        }
        ?>
    </div>
    
    <!-- Footer -->
    <div class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>BookNest</h5>
                    <p>Rent your favorite books at affordable prices. Return when you're done.</p>
                </div>
                <div class="col-md-2">
                    <h6>Help</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">FAQ</a></li>
                        <li><a href="#" class="text-white">Returns</a></li>
                        <li><a href="#" class="text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Policy</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Return Policy</a></li>
                        <li><a href="#" class="text-white">Terms of Use</a></li>
                        <li><a href="#" class="text-white">Privacy</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Social</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Facebook</a></li>
                        <li><a href="#" class="text-white">Twitter</a></li>
                        <li><a href="#" class="text-white">Instagram</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Company</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">About Us</a></li>
                        <li><a href="#" class="text-white">Careers</a></li>
                        <li><a href="#" class="text-white">Blog</a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center mt-3">
                <p class="small mb-0">© 2025 BookBazaar. All rights reserved.</p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>