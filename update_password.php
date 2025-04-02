<?php
session_start();
require 'database_connection.php';  // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if ($new_password !== $confirm_password) {
        die("Passwords do not match!");
    }

    $email = $_SESSION['email'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in database
    $query = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashed_password, $email);

    if ($stmt->execute()) {
        unset($_SESSION['email']);  // Clear session
        echo "Password updated successfully. <a href='login.php'>Login</a>";
    } else {
        echo "Error updating password.";
    }
}
?>
