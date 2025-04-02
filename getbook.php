<?php
session_start();
include_once('database_connection.php');

// Initialize cart session if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding book to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_STRING);
    $condition = filter_input(INPUT_POST, 'condition', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
    $imagePath = filter_input(INPUT_POST, 'image_path', FILTER_SANITIZE_URL);

    if ($bookId && $title && $author && $isbn && $condition && $price && $imagePath) {
        $cartItem = [
            'id' => $bookId,
            'title' => $title,
            'author' => $author,
            'isbn' => $isbn,
            'condition' => $condition,
            'price' => $price,
            'image_path' => $imagePath,
            'quantity' => 1
        ];

        // Check if book is already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $bookId) {
                $item['quantity'] += 1;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = $cartItem;
        }
    }
    header("Location: cart.php");
    exit();
}

// Fetch all available books
$sql = "SELECT * FROM books WHERE quantity > 0";
$result = $conn->query($sql);
if (!$result) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second-Hand Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #333; }
        .book-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .book-item { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: transform 0.2s; text-align: center; }
        .book-item:hover { transform: translateY(-5px); }
        .book-item img { max-width: 100%; height: auto; border-radius: 5px; }
        .book-info { font-size: 14px; color: #666; text-align: left; margin-top: 5px; }
        .book-info strong { color: #333; }
        .button { display: inline-block; padding: 10px 15px; text-decoration: none; border-radius: 5px; transition: 0.3s; border: none; cursor: pointer; }
        .add-cart { background: #28a745; color: white; margin-right: 5px; }
        .add-cart:hover { background: #218838; }
        .buy-now { background: #dc3545; color: white; }
        .buy-now:hover { background: #c82333; }
        .cart-button { float: right; padding: 10px 15px; background: #007bff; color: white; border-radius: 5px; text-decoration: none; }
        .cart-button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Explore Second-Hand Books</h1>
        <a href="cart.php" class="cart-button">View Cart (<?php echo count($_SESSION['cart']); ?>)</a>
        <div class="book-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="book-item">
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Book Image">
                    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                    <div class="book-info">
                        <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                        <p><strong>ISBN:</strong> <?php echo htmlspecialchars($row['ISBN_no']); ?></p>
                        <p><strong>Condition:</strong> <?php echo htmlspecialchars($row['condition_of_book']); ?></p>
                        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?></p>
                    </div>
                    <form action="" method="POST" style="display: inline;">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($row['title']); ?>">
                        <input type="hidden" name="author" value="<?php echo htmlspecialchars($row['author']); ?>">
                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($row['ISBN_no']); ?>">
                        <input type="hidden" name="condition" value="<?php echo htmlspecialchars($row['condition_of_book']); ?>">
                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">
                        <input type="hidden" name="image_path" value="<?php echo htmlspecialchars($row['image_path']); ?>">
                        <button type="submit" name="add_to_cart" class="button add-cart">Add to Cart</button>
                    </form>
                    <form action="purchase.php" method="POST" style="display: inline;">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <input type="hidden" name="title" value="<?php echo htmlspecialchars($row['title']); ?>">
                        <input type="hidden" name="author" value="<?php echo htmlspecialchars($row['author']); ?>">
                        <input type="hidden" name="isbn" value="<?php echo htmlspecialchars($row['ISBN_no']); ?>">
                        <input type="hidden" name="condition" value="<?php echo htmlspecialchars($row['condition_of_book']); ?>">
                        <input type="hidden" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">
                        <input type="hidden" name="image_path" value="<?php echo htmlspecialchars($row['image_path']); ?>">
                        <button type="submit" class="button buy-now">Buy Now</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
