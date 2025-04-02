<?php
session_start();
require 'database_connection.php';  // Database connection file

// Assuming user ID is stored in session after login
$userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($userId === 0) {
    echo "Please log in to view your cart.";
    exit;
}

// Remove book from cart
if (isset($_GET['remove'])) {
    $bookId = intval($_GET['remove']);
    $deleteQuery = "DELETE FROM cart WHERE user_id = ? AND book_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    if ($stmt) {
        $stmt->bind_param("ii", $userId, $bookId);
        $stmt->execute();
        $stmt->close();
    }
}

$query = "SELECT * FROM cart WHERE user_id = ?";

// Fetch available books
$booksQuery = "SELECT * FROM cart";
$booksResult = $conn->query($booksQuery);

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total price
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .remove-btn {
            background: #d9534f;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
            border: none;
            cursor: pointer;
        }
        .remove-btn:hover {
            background: #c9302c;
        }
        .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            margin-top: 20px;
        }
        .empty-cart {
            text-align: center;
            font-size: 20px;
            color: #999;
            margin-top: 20px;
        }
        .empty-cart i {
            font-size: 40px;
            color: #bbb;
        }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            background: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-shopping-cart"></i> Your Cart</h1>
    <a href="homepage.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Shop</a>

    <?php if ($result->num_rows > 0) 
     ?>

    <h2>Your Cart Books</h2>
    <?php if ($booksResult->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($book = $booksResult->fetch_assoc()) { 
                ?>
                    <tr>
                        <td>
                            <img src="<?php echo !empty($book['image_path']) ? $book['image_path'] : 'images/no-image.png'; ?>" 
                                 alt="Book Image" style="width: 80px; height: 100px;">
                        </td>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td>₹<?php echo number_format($book['price'], 2); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No books available.</p>
    <?php } ?>

</div>

</body>
</html>
