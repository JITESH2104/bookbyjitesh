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
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["status" => "error", "message" => "Please log in to book a book for the future."]));
}

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Validate form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'], $_POST['start_date'])) {
    $book_id = intval($_POST['book_id']);
    $start_date = $_POST['start_date'];

    // Check if the selected date is in the future
    if (strtotime($start_date) < strtotime(date("Y-m-d"))) {
        die(json_encode(["status" => "error", "message" => "Invalid date! You can only book for future dates."]));
    }

    // Check if the book is already rented on the selected date
    $check_sql = "SELECT * FROM future_rentals WHERE book_id = ? AND start_date = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("is", $book_id, $start_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die(json_encode(["status" => "error", "message" => "This book is already booked for the selected date."]));
    }

    // Insert future booking
    $insert_sql = "INSERT INTO future_rentals (user_id, book_id, start_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iis", $user_id, $book_id, $start_date);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Future booking confirmed for " . htmlspecialchars($start_date)]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request!"]);
}

$conn->close();
?>
