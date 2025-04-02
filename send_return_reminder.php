<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'C:\\xampp\\htdocs\\book\\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer Files
require 'C:\xampp\htdocs\book\vendor\autoload.php';

// Secure Credentials
$email_sender = 'jitupardhi2006@gmail.com'; // Replace with your Gmail
$app_password = 'zdtt qqdw askl eqjr';    // Store in an env file instead of here

// ✅ Get current date
$current_date = date('Y-m-d');
echo "✅ Today's Date: $current_date<br>";

// ✅ Fetch orders where return date is approaching (within 2 days) or overdue
$query = "SELECT o.customer_email, o.full_name, b.title, o.return_date
          FROM orders o
          JOIN books b ON o.book_id = b.id
          WHERE o.return_date BETWEEN ? AND DATE_ADD(?, INTERVAL 2 DAY)";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("❌ Error preparing query: " . $conn->error);
}

$stmt->bind_param("ss", $current_date, $current_date);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    echo "✅ Query executed successfully!<br>";
    echo "Number of rows: " . $result->num_rows . "<br>";
} else {
    die("❌ SQL Error: " . $stmt->error);
}

// ✅ Send emails to customers with due books
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        sendReturnReminder($row['customer_email'], $row['full_name'], $row['title'], $row['return_date']);
    }
} else {
    echo "✅ No books due for return soon.<br>";
}

// ✅ Email sending function
function sendReturnReminder($email, $name, $book_title, $return_date) {
    global $email_sender, $app_password;

    $mail = new PHPMailer(true);
    try {
        // ✅ SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email_sender;
        $mail->Password = $app_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // ✅ Sender and recipient
        $mail->setFrom($email_sender, 'Book Rental Service');
        $mail->addAddress($email, $name);

        // ✅ Email subject and HTML body
        $mail->isHTML(true);
        $mail->Subject = "Book Return Reminder - $book_title";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 10px; border: 1px solid #ddd; border-radius: 5px;'>
                <p>Dear <strong>$name</strong>,</p>
                <p>This is a friendly reminder that your borrowed book <strong>'$book_title'</strong> is due for return on <strong>$return_date</strong>.</p>
                <p>Please return the book on or before the due date to avoid any late fees.</p>
                <p style='color: gray; font-size: 12px;'>
                    Thank you for using our Book Rental Service!<br>
                    <em>For any queries, contact support@example.com.</em>
                </p>
            </div>
        ";

        // ✅ Send email
        if ($mail->send()) {
            echo "✅ Reminder sent to $email for book: $book_title<br>";
        } else {
            echo "❌ Email could not be sent. Error: {$mail->ErrorInfo}<br>";
        }
    } catch (Exception $e) {
        echo "❌ Email error: {$mail->ErrorInfo}<br>";
    }
}

// ✅ Close resources
$stmt->close();
$conn->close();
?>
