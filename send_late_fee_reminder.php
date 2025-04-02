<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'C:\\xampp\\htdocs\\book\\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer Files
require 'C:\\xampp\\htdocs\\book\\vendor\\autoload.php';

// Secure Credentials
$email_sender = 'jitupardhi2006@gmail.com';
$app_password = 'zdtt qqdw askl eqjr';  // Replace with environment variable

// ✅ Get current date
$current_date = date('Y-m-d');
echo "✅ Today's Date: $current_date<br>";

// ✅ Fetch overdue books with deposit info
$query = "SELECT o.id, o.customer_email, o.full_name, b.title, 
                 o.return_date, o.deposit,
                 DATEDIFF(?, o.return_date) AS overdue_days 
          FROM orders o
          JOIN books b ON o.book_id = b.id
          WHERE o.return_date < ?";

$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("❌ Error preparing query: " . $conn->error);
}

$stmt->bind_param("ss", $current_date, $current_date);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    echo "✅ Query executed successfully!<br>";
    echo "Number of overdue books: " . $result->num_rows . "<br>";
} else {
    die("❌ SQL Error: " . $stmt->error);
}

// ✅ Process overdue books
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['id'];
        $email = $row['customer_email'];
        $name = $row['full_name'];
        $book_title = $row['title'];
        $return_date = $row['return_date'];
        $overdue_days = max(0, $row['overdue_days']);
        $deposit_amount = $row['deposit'];  // Fetch deposit

        // ✅ Calculate Late Fee (₹10 per day)
        $late_fee = $overdue_days * 10;

        // ✅ Deduct from Deposit
        $remaining_deposit = max(0, $deposit_amount - $late_fee);

        // ✅ Update Late Fee and Deposit in Database
        $update_query = "UPDATE orders SET late_fee = ?, deposit = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        if ($update_stmt) {
            $update_stmt->bind_param("dii", $late_fee, $remaining_deposit, $order_id);
            $update_stmt->execute();
            $update_stmt->close();
            echo "✅ Late fee ₹$late_fee deducted from deposit. Remaining deposit: ₹$remaining_deposit<br>";
        } else {
            echo "❌ Failed to update late fee: " . $conn->error . "<br>";
        }

        // ✅ Send Late Fee Notification
        sendLateFeeReminder($email, $name, $book_title, $return_date, $overdue_days, $late_fee, $remaining_deposit);
    }
} else {
    echo "✅ No overdue books.<br>";
}

// ✅ Email sending function for Late Fee
function sendLateFeeReminder($email, $name, $book_title, $return_date, $overdue_days, $late_fee, $remaining_deposit) {
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
        $mail->Subject = "Overdue Book & Late Fee - $book_title";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 10px; border: 1px solid #ddd; border-radius: 5px;'>
                <p>Dear <strong>$name</strong>,</p>
                <p>Your borrowed book <strong>'$book_title'</strong> was due on <strong>$return_date</strong>.</p>
                <p>You are <strong>$overdue_days days overdue</strong>, and a late fee of <strong>₹$late_fee</strong> has been applied.</p>
                <p>The late fee has been deducted from your deposit. Your remaining deposit balance is <strong>₹$remaining_deposit</strong>.</p>
                <p>Please return the book as soon as possible to avoid additional charges.</p>
                <p style='color: gray; font-size: 12px;'>
                    Thank you for using our Book Rental Service!<br>
                    <em>For any queries, contact support@example.com.</em>
                </p>
            </div>
        ";

        // ✅ Send email
        if ($mail->send()) {
            echo "✅ Late Fee Notification sent to $email for book: $book_title<br>";
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
