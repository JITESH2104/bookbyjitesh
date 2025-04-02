<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "book_rental");

// Check database connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$error = "";
$step = $_GET['step'] ?? 'verify_user';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($step == 'verify_user' && isset($_POST['email'], $_POST['mobile_number'], $_POST['name'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $mobile_number = $conn->real_escape_string($_POST['mobile_number']);
        $name = $conn->real_escape_string($_POST['name']);

        // Check if user details match
        $query = "SELECT id FROM users WHERE email = '$email' AND mobile_number = '$mobile_number' AND name = '$name'";
        $result = $conn->query($query);

        if ($result && $result->num_rows == 1) {
            $_SESSION['reset_email'] = $email;
            header("Location: forgot_password.php?step=reset_password");
            exit();
        } else {
            $error = "User details do not match. Please try again.";
        }
    } elseif ($step == 'reset_password' && isset($_POST['new_password']) && isset($_SESSION['reset_email'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
        $email = $_SESSION['reset_email'];

        if ($conn->query("UPDATE users SET password = '$new_password' WHERE email = '$email'")) {
            session_unset();
            session_destroy();
            header("Location: login.php?reset=success");
            exit();
        } else {
            $error = "Failed to update the password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: url('images/gettyimages-1197265944-612x612.jpg') no-repeat center center fixed; background-size: cover; color: #333; }
        .container { width: 100%; max-width: 400px; background-color: rgba(255, 255, 255, 0.9); padding: 30px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; text-align: center; }
        .container h2 { font-size: 1.8em; margin-bottom: 20px; }
        .container form { display: flex; flex-direction: column; }
        .container input { padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; width: 100%; }
        .container input[type="submit"] { background-color: #333; color: #fff; cursor: pointer; transition: background-color 0.3s ease; }
        .container input[type="submit"]:hover { background-color: #555; }
        .container p.error { color: #e74c3c; margin-top: 10px; font-size: 0.9em; }
        .container a { display: block; margin-top: 15px; color: #333; text-decoration: none; font-size: 0.9em; transition: color 0.3s ease; }
        .container a:hover { color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($step == 'verify_user'): ?>
            <h2>Verify Your Identity</h2>
            <form action="forgot_password.php?step=verify_user" method="POST">
                <input type="text" name="name" placeholder="Enter Your Name" required>
                <input type="email" name="email" placeholder="Enter Your Email" required>
                <input type="text" name="mobile_number" placeholder="Enter Your Mobile Number" required>
                <input type="submit" value="Verify">
            </form>
        <?php elseif ($step == 'reset_password'): ?>
            <h2>Reset Your Password</h2>
            <form action="forgot_password.php?step=reset_password" method="POST">
                <input type="password" name="new_password" placeholder="Enter New Password" required>
                <input type="submit" value="Reset Password">
            </form>
        <?php endif; ?>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <a href="login.php">Back to login</a>
    </div>
</body>
</html>
