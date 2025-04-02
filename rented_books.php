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

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Please log in to view rented books.");
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Fetch all rented books for the logged-in user
$sql = "SELECT r.*, b.id AS book_id, b.title AS book_title, b.image_path AS book_image, b.rent_price, 
               u.name AS owner_name, u.mobile_number AS owner_mobile,
               r.rental_date, r.rental_days
        FROM rentals r
        JOIN books b ON r.id = b.id
        JOIN users u ON b.user_id = u.id
        WHERE r.renter_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rented Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .book-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }
        .book-card {
            width: 280px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
            text-align: center;
            padding: 10px;
        }
        .book-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
            display: block;
        }
        .book-details {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">My Rented Books</h2>
        <div class="book-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="book-card">
                        <?php 
                        $image_path = htmlspecialchars($row['book_image']);
                        $full_image_path = "http://localhost/book/uploads/" . $image_path;

                        // Check if file exists; if not, use a placeholder image
                        if (!file_exists(__DIR__ . "/uploads/" . $image_path) || empty($image_path)) {
                            $full_image_path = "http://localhost/book/uploads/placeholder.jpg";
                        }

                        // Calculate return date
                        $rental_date = $row['rental_date'];
                        $rental_days = $row['rental_days'];
                        $return_date = date('Y-m-d', strtotime($rental_date . " + $rental_days days"));
                        ?>
                        <img src="<?php echo $full_image_path; ?>" alt="Book Image" onerror="this.src='http://localhost/book/uploads/placeholder.jpg'">
                        <div class="book-details">
                            <h5><?php echo htmlspecialchars($row['book_title']); ?></h5>
                            <p><strong>Owner:</strong> <?php echo htmlspecialchars($row['owner_name']); ?></p>
                            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($row['owner_mobile']); ?></p>
                            <p><strong>Rental Date:</strong> <?php echo htmlspecialchars($rental_date); ?></p>
                            <p><strong>Rental Days:</strong> <?php echo htmlspecialchars($rental_days); ?></p>
                            <p><strong>Return Date:</strong> <?php echo htmlspecialchars($return_date); ?></p>
                            <p><strong>Price:</strong> ₹<?php echo isset($row['total_price']) ? htmlspecialchars($row['total_price']) : 'N/A'; ?></p>
                            <p><strong>Status:</strong> <?php echo isset($row['status']) ? htmlspecialchars($row['status']) : 'Pending'; ?></p>
                           

                            <!-- Future Booking Form -->
                            <form class="futureBookingForm mt-3">
                                <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                <label for="start_date_<?php echo $row['book_id']; ?>">Select Start Date:</label>
                                <input type="date" id="start_date_<?php echo $row['book_id']; ?>" name="start_date" required class="form-control">
                                <label for="end_date_<?php echo $row['book_id']; ?>">Select End Date:</label>
                                <input type="date" id="end_date_<?php echo $row['book_id']; ?>" name="end_date" required class="form-control">
                                <button type="submit" class="btn btn-success mt-2">Book for Future</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-danger">No rented books found.</p>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="homepage.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>

    <script>
    document.querySelectorAll(".futureBookingForm").forEach(form => {
        form.addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form from submitting normally

            let formData = new FormData(this);

            fetch("future_booking.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Show alert with response message
            })
            .catch(error => console.error("Error:", error));
        });
    });
    </script>
</body>
</html>

<?php $stmt->close(); $conn->close(); ?>
