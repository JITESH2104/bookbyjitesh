<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection file
include 'C:/xampp/htdocs/book/database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Login logic
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hardcoded Admin Login
        if ($email === 'jitupardhi2006@gmail.com' && $password === 'jitu2104') {
            $_SESSION['user_id'] = 'admin';
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'Admin';
            header("Location: admin_dashboard.php?login=success&role=Admin");
            exit();
        }

        $query = "SELECT id, password, role FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashedPassword, $role);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;
                header("Location: homepage.php?login=success&role=" . urlencode($role));
                exit();
            } else {
                echo "Invalid password. Please try again.";
            }
        } else {
            echo "Email not found.";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link rel="stylesheet" href="loginRes.css">
</head>
<body>
    <div class="page-container">
        <div class="image-section">
            <img src="img1.png" width="310px" height="300px" alt="Welcome Image">
            <h2>Welcome to Our BookNest</h2>
            <p>Your gateway to knowledge and discovery</p>
        </div>
        <div class="auth-container">
            <div class="form-toggle">
                <button class="toggle-btn active" onclick="toggleForm('login')">Login</button>
                <a href="register.php"><button class="toggle-btn">Register</button></a>
            </div>

            <!-- Login Form -->
            <div id="loginForm" class="form-section active">
                <form method="POST">
                    <div class="form-group">
                        <label for="loginEmail">Email</label>
                        <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="auth-button" name="login">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
