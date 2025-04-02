<?php
session_start();
include 'C:\\xampp\\htdocs\\book\\database_connection.php'; // Ensure correct path

// Check if book ID is passed
if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
    echo "<div class='alert alert-danger text-center'>Invalid or missing book ID!</div>";
    exit();
}

$book_id = intval($_POST['book_id']);

// ✅ Fetch book, return date, and image path from orders
$query = "SELECT b.title, b.author, b.book_publisher, b.availability_status, 
                 b.image_path, COALESCE(o.return_date, NULL) AS return_date 
          FROM books b
          LEFT JOIN orders o ON b.id = o.book_id
          WHERE b.id = ? 
          ORDER BY o.return_date DESC 
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($book = $result->fetch_assoc()) {
    $availability_status = $book['availability_status'];
    $return_date = $book['return_date'];
    $image_path = $book['image_path'] ?? 'default.png'; // Fallback to default if no image
} else {
    echo "<div class='alert alert-danger text-center'>Book not found!</div>";
    exit();
}

// ✅ Check if the book is rented and show return date
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Availability - BookNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7e9de;
            font-family: 'Montserrat', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
        }

        .btn {
            border-radius: 5px;
        }

        .book-image {
            display: block;
            margin: 0 auto 20px auto;
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <h3 class="text-center">Book Availability</h3>

            <!-- ✅ Display Book Image -->
            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Book Image" class="book-image">

            <p><strong>Title:</strong> <?php echo htmlspecialchars($book['title']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Publisher:</strong> <?php echo htmlspecialchars($book['book_publisher']); ?></p>

            <?php
            // ✅ Check if the book is rented or available
            if ($availability_status === 'rented') {
                echo "<p class='text-danger'><strong>Status:</strong> Currently Rented</p>";
                if (!empty($return_date)) {
                    echo "<p><strong>Expected Return Date:</strong> " . date('d M, Y', strtotime($return_date)) . "</p>";
                } else {
                    echo "<p><strong>Return Date:</strong> Not provided yet.</p>";
                }
            } elseif ($availability_status === 'available') {
                echo "<p class='text-success'><strong>Status:</strong> Available</p>";
            } else {
                echo "<p class='text-warning'><strong>Status:</strong> Unknown status!</p>";
            }
            ?>

            <div class="text-center mt-3">
                <a href="homepage.php" class="btn btn-primary">Back to Home</a>
                <?php if ($availability_status === 'available') : ?>
                    <form method="POST" action="rent_now.php" style="display:inline;">
                        <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                        <button type="submit" class="btn btn-success">Rent Now</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>

</html>
