<?php
$servername = "localhost:3306";

$username = "root";
$password = ""; // Default password for XAMPP (update if necessary)
$dbname = "book_rental"; // Your database name

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set for handling special characters
$conn->set_charset("utf8mb4");
?>
