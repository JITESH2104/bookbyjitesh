<?php
$conn = new mysqli("localhost", "root", "", "book_rental");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ? AND verified = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Activate the user
        $stmt = $conn->prepare("UPDATE users SET verified = 1, verification_token = NULL WHERE verification_token = ?");
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            echo "<h2>Email verified successfully! You can now <a href='login.php'>Login</a>.</h2>";
        } else {
            echo "<h2>Verification failed. Please try again.</h2>";
        }
    } else {
        echo "<h2>Invalid or expired token.</h2>";
    }
    $stmt->close();
}
$conn->close();
?>
