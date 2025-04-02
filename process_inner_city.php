<?php
// Start session and enable error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer files
require 'C:\xampp\htdocs\book\vendor\autoload.php';


// Include database connection
include 'C:\xampp\htdocs\book\database_connection.php';  // Update with correct path

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dispatch'])) {
    // Get form data
    $mobile_number = htmlspecialchars(trim($_POST['mobile_number']));
    $meeting_address = htmlspecialchars(trim($_POST['meeting_address']));
    $meeting_time = htmlspecialchars(trim($_POST['meeting_time']));

    // ✅ Retrieve customer email from the database
    $query = "SELECT customer_email FROM view_requests LIMIT 1";  // Modify query if necessary to filter the correct record
    $result = $conn->query($query);

    // Check if a valid record is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customer_email = $row['customer_email'];

        // ✅ Email details
        $subject = "Parcel Dispatch Details";
        $message = "
            <h3>Dispatch Details</h3>
            <p><strong>Mobile Number:</strong> $mobile_number</p>
            <p><strong>Meeting Address:</strong> $meeting_address</p>
            <p><strong>Meeting Time:</strong> $meeting_time</p>
            <p>Thank you for using our dispatch service!</p>
        ";

        // ✅ Send Email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'jitupardhi2006@gmail.com'; // Your Gmail address
            $mail->Password = 'zdtt qqdw askl eqjr'; // Your App Password or SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Sender and recipient settings
            $mail->setFrom('jitupardhi2006@gmail.com', 'Parcel Dispatch Service');
            $mail->addAddress($customer_email); // Customer's email

            // Email content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send email
            if ($mail->send()) {
                echo "<script>alert('Dispatch details sent successfully to the customer!'); window.location.href='homepage.php';</script>";
            } else {
                echo "<script>alert('Error sending email. Please try again.'); window.location.href='homepage.php';</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Mail could not be sent. Error: {$mail->ErrorInfo}'); window.location.href='homepage.php';</script>";
        }
    } else {
        echo "<script>alert('No customer found in the database.'); window.location.href='homepage.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request! Please submit the form correctly.'); window.location.href='homepage.php';</script>";
}
?>
