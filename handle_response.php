<?php
session_start();
include 'C:\\xampp\\htdocs\\book\\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\\xampp\\vendor\\PHPMailer\\phpmailer\\src\\Exception.php';
require 'C:\\xampp\\vendor\\PHPMailer\\phpmailer\\src\\PHPMailer.php';
require 'C:\\xampp\\vendor\\PHPMailer\\phpmailer\\src\\SMTP.php';

// Check for valid request
if (!isset($_GET['token'], $_GET['status'])) {
    exit("Invalid request!");
}

$token = $_GET['token'];
$status = $_GET['status'];

// Validate token in the database
$query = "SELECT * FROM rental_responses WHERE token = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($request = $result->fetch_assoc()) {
    $book_id = $request['book_id'];
    $customer_email = $request['customer_email'];

    if ($status === "accepted") {
        // Redirect to rental_form.php on acceptance
        $_SESSION['rental_token'] = $token;
        $_SESSION['book_id'] = $book_id;
        $_SESSION['customer_email'] = $customer_email;

        header("Location: rental_form.php");
        exit();
    } elseif ($status === "declined") {
        // Send decline email to the requester
        if (sendDeclineEmail($customer_email)) {
            echo "<script>alert('Request Declined. Notification sent to the requester.'); window.location.href = 'homepage.php';</script>";
        } else {
            echo "<script>alert('Error sending decline email.'); window.location.href = 'homepage.php';</script>";
        }
    }
} else {
    echo "<script>alert('Invalid or expired token!'); window.location.href = 'homepage.php';</script>";
}

$stmt->close();

// Function to send decline email to the customer
function sendDeclineEmail($customer_email)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jitupardhi2006@gmail.com'; // Sender Email
        $mail->Password = 'zdtt qqdw askl eqjr'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental Service');
        $mail->addAddress($customer_email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Book Rental Request Declined';
        $mail->Body = "<p>Hello,</p>
                       <p>Unfortunately, the book owner has declined your rental request.</p>
                       <p>Thank you for using our service!</p>";

        return $mail->send(); // Return true if successful
    } catch (Exception $e) {
        return false;
    }
}
?>
