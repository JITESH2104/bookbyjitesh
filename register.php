<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\xampp\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'C:\xampp\vendor\phpmailer\phpmailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['send_otp'])) {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;

        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jitupardhi2006@gmail.com'; 
            $mail->Password = 'zdtt qqdw askl eqjr'; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('jitupardhi2006@gmail.com', 'OTP Verification');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "<h3>Your OTP code is: <b>$otp</b></h3>";

            $mail->send();
            $_SESSION['message'] = "OTP has been sent to your email!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
        }
    } elseif (isset($_POST['verify_otp'])) {
        $enteredOtp = $_POST['otp'];
        if ($enteredOtp == $_SESSION['otp']) {
            unset($_SESSION['otp']);
            $_SESSION['verified_email'] = $_SESSION['email']; // Store email for autofill
            header("Location: register_form.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid OTP. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff7e6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #ff6600;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ff9800;
            border-radius: 5px;
            outline: none;
        }
        button {
            background-color: #ff6600;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        button:hover {
            background-color: #e65c00;
        }
        .message {
            color: green;
            margin: 10px 0;
        }
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form action="" method="POST">
            <label for="email">Enter Email:</label>
            <input type="email" name="email" required placeholder="Enter your email">
            <button type="submit" name="send_otp">Send OTP</button>
        </form>

        <form action="" method="POST">
            <label for="otp">Enter OTP:</label>
            <input type="text" name="otp" required placeholder="Enter OTP" maxlength="6">
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    </div>
</body>
</html>
