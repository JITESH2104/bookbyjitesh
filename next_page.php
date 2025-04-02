<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details with Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Book Details</h2>
            </div>
            <div class="card-body">
                <?php
                // Database connection
                $conn = new mysqli("localhost", "root", "", "book_rental");

                // Check connection
                if ($conn->connect_error) {
                    die("<div class='alert alert-danger'>Connection failed: " . $conn->connect_error . "</div>");
                }

                // Check if book_id is received from the previous page
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['book_id'])) {
                    $book_id = $conn->real_escape_string($_POST['book_id']);

                    // Fetch book details from the database
                    $sql = "SELECT book_id, title, author, rent_price FROM books WHERE book_id = '$book_id'";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo "<p><strong>Book ID:</strong> " . htmlspecialchars($row['book_id']) . "</p>";
                        echo "<p><strong>Title:</strong> " . htmlspecialchars($row['title']) . "</p>";
                        echo "<p><strong>Author:</strong> " . htmlspecialchars($row['author']) . "</p>";
                        echo "<p><strong>Rent Price:</strong> ₹" . htmlspecialchars($row['rent_price']) . "</p>";
                        echo "<hr>";

                        // Payment form with automatic rent price
                        echo "<h4>Proceed to Payment</h4>";
                        echo '<form action="process_payment.php" method="POST">';
                        echo '<input type="hidden" name="book_id" value="' . htmlspecialchars($row['book_id']) . '">';
                        echo '<div class="mb-3">';
                        echo '<label for="paymentAmount" class="form-label">Payment Amount (INR):</label>';
                        echo '<input type="number" class="form-control" id="paymentAmount" name="amount" value="' . htmlspecialchars($row['rent_price']) . '" readonly>';
                        echo '</div>';
                        echo '<button type="submit" class="btn btn-success">Pay Now</button>';
                        echo '</form>';
                    } else {
                        echo "<p class='text-danger'>No details found for Book ID: " . htmlspecialchars($book_id) . "</p>";
                    }
                } else {
                    echo "<p class='text-warning'>No Book ID provided. Please go back and try again.</p>";
                }

                $conn->close();
                ?>
            </div>
            <div class="card-footer text-center">
                <a href="index.php" class="btn btn-secondary">Go Back</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
