<?php 
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "book_rental";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == 0) {
    echo "<script>
        alert('You must be logged in to view rented books!');
        window.location.href = 'login.php';
    </script>";
    exit(); // Stop further execution if not logged in
}

// Get logged-in user ID
$user_id = $_SESSION['user_id'];

// ✅ Fetch all rented books for the logged-in user
$sql = "SELECT r.*, b.id AS book_id, b.title AS book_title, b.author, b.rent_price, 
               b.image_path, u.name AS owner_name, u.mobile_number AS owner_mobile,
               r.rental_date, r.rental_days
        FROM rentals r
        JOIN books b ON r.book_id = b.id
        JOIN users u ON b.user_id = u.id
        WHERE r.renter_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Display rented books
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='book'>";
        echo "<img src='" . $row['image_path'] . "' alt='Book Image'>";
        echo "<div class='book-info'>";
        echo "<div class='book-title'>" . htmlspecialchars($row['book_title']) . "</div>";
        echo "<div class='book-details'>";
        echo "<p>Author: " . htmlspecialchars($row['author']) . "</p>";
        echo "<p>Rent Price: ₹" . number_format($row['rent_price'], 2) . "</p>";
        echo "<p>Rented On: " . htmlspecialchars($row['rental_date']) . "</p>";
        echo "<p>Rental Duration: " . htmlspecialchars($row['rental_days']) . " days</p>";
        echo "<p>Owner: " . htmlspecialchars($row['owner_name']) . " (📞 " . htmlspecialchars($row['owner_mobile']) . ")</p>";
        echo "</div></div></div>";
    }
} else {
    echo "<p style='text-align: center; color: red;'>❌ No books rented yet!</p>";
}

// Close connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rented Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .book-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .book-image {
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        .card-body {
            display: flex;
            flex-direction: column;
        }
        .book-title {
            font-weight: bold;
            font-size: 1.1rem;
            height: 50px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .status-rented {
            background-color: #fbf5e0;
            color: #a88734;
        }
        .status-returned {
            background-color: #e6f4ea;
            color: #137333;
        }
        .status-overdue {
            background-color: #fce8e6;
            color: #c5221f;
        }
        .price {
            color: #B12704;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .owner-info {
            font-size: 0.9rem;
            color: #555;
        }
        .date-info {
            margin-top: auto;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center mb-4">My Rented Books</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $return_date = date('Y-m-d', strtotime($row['rental_date'] . " + " . $row['rental_days'] . " days"));
                    $status = isset($row['status']) ? $row['status'] : 'rented';
                    
                    // Determine if book is overdue
                    $today = date('Y-m-d');
                    if ($status == 'rented' && $today > $return_date) {
                        $status = 'overdue';
                    }
                    
                    // Determine status class for styling
                    $statusClass = 'status-rented';
                    if ($status == 'returned') {
                        $statusClass = 'status-returned';
                    } elseif ($status == 'overdue') {
                        $statusClass = 'status-overdue';
                    }
                    ?>
                    
                    <div class="col">
                        <div class="card book-card">
                            <div class="position-relative">
                                <img 
                                    src="<?php echo isset($row['image_url']) && !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : 'images/default-book.jpg'; ?>" 
                                    class="card-img-top book-image" 
                                    alt="<?php echo htmlspecialchars($row['book_title']); ?>"
                                >
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge <?php echo $statusClass; ?> py-2 px-3">
                                        <?php 
                                        if ($status == 'rented') {
                                            echo '<i class="fas fa-clock me-1"></i> Active';
                                        } elseif ($status == 'returned') {
                                            echo '<i class="fas fa-check-circle me-1"></i> Returned';
                                        } elseif ($status == 'overdue') {
                                            echo '<i class="fas fa-exclamation-circle me-1"></i> Overdue';
                                        }
                                        ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="book-title mb-2"><?php echo htmlspecialchars($row['book_title']); ?></div>
                                
                                <?php if (isset($row['author'])): ?>
                                <div class="mb-1 text-muted"><?php echo htmlspecialchars($row['author']); ?></div>
                                <?php endif; ?>
                                
                                <div class="price mb-2">₹<?php echo isset($row['rent_price']) ? htmlspecialchars($row['rent_price']) : 'N/A'; ?></div>
                                
                                <div class="owner-info mb-2">
                                    <small><strong>Owner:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></small><br>
                                    <small><strong>Contact:</strong> <?php echo htmlspecialchars($row['owner_mobile']); ?></small>
                                </div>
                                
                                <div class="date-info mt-auto">
                                    <div class="d-flex justify-content-between">
                                        <small><strong>Rented:</strong> <?php echo htmlspecialchars($row['rental_date']); ?></small>
                                        <small><strong>Days:</strong> <?php echo htmlspecialchars($row['rental_days']); ?></small>
                                    </div>
                                    <div class="mt-1">
                                        <small><strong>Return by:</strong> <?php echo htmlspecialchars($return_date); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i> You haven't rented any books yet.
            </div>
        <?php endif; ?>
        
        <div class="text-center mt-4 mb-5">
            <a href="homepage.php" class="btn btn-primary">
                <i class="fas fa-home me-2"></i> Back to Home
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
$stmt->close();
$conn->close();
?>