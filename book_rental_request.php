<?php
session_start();
include_once('database_connection.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}

// Ensure the book_id is provided and valid
if (!isset($_GET['book_id']) || !is_numeric($_GET['book_id'])) {
    echo "Invalid book ID.";
    exit();
}

$book_id = $_GET['book_id']; // The ID of the book being rented
$user_id = $_SESSION['user_id']; // The ID of the logged-in user

// Step 1: Fetch the book details and the owner of the book
$query = "SELECT b.title, b.user_id AS owner_id, u.name AS owner_name 
          FROM rentals b 
          JOIN users u ON b.user_id = u.id 
          WHERE b.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "The book does not exist.";
    exit();
}

$book = $result->fetch_assoc();
$owner_id = $book['owner_id'];
$book_title = htmlspecialchars($book['title']); // Sanitize book title
$owner_name = htmlspecialchars($book['owner_name']); // Sanitize owner name

// Step 2: Send a message to the book owner about the rental request
$message = "Hello, I would like to rent your book titled '$book_title'. Please respond.";
$insertMessageQuery = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($insertMessageQuery);
$stmt->bind_param("iis", $user_id, $owner_id, $message);

if ($stmt->execute()) {
    echo "Rental request sent to $owner_name!";
} else {
    echo "There was an issue sending your rental request. Please try again later.";
}

// Close the statement and database connection
$stmt->close();
$conn->close();
?>
