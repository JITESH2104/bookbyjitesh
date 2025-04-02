<?php
session_start();
require 'C:\xampp\htdocs\book\database_connection.php'; // Include the database connection
require 'C:\xampp\vendor\autoload.php';  // Use Composer's autoloader

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get rental ID from request
if (isset($_POST['rental_id'])) {
    $rental_id = $_POST['rental_id'];

    // Fetch rental details
    $query = "SELECT r.*, b.title, u.email AS owner_email 
              FROM rentals r
              JOIN books b ON r.id = b.id
              JOIN users u ON r.owner_id = u.id
              WHERE r.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $book_title = $row['title'];
        $owner_email = $row['owner_email'];

        // Update return status
        $update_query = "UPDATE rentals SET return_status = 'Returned', return_date = NOW() WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("i", $rental_id);
        if ($stmt->execute()) {
            // Send Email Notification
            $mail = new PHPMailer(true);
            try {
                // SMTP Settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jitupardhi2006@.com'; // Replace with your Gmail
                $mail->Password = 'zdtt qqdw askl eqjr'; // Use Gmail App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email Content
                $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental System');
                $mail->addAddress($owner_email); // Send to book owner
                $mail->Subject = "Book Returned: $book_title";
                $mail->Body = "Dear Owner,\n\nThe book '$book_title' has been returned by the renter.\n\nBest regards,\nBook Rental System";

                // Send Email
                $mail->send();
                echo "<script>alert('Book returned successfully! Email sent to owner.'); window.location.href='my_customer_order.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Book returned, but email could not be sent: {$mail->ErrorInfo}'); window.location.href='my_customer_order.php';</script>";
            }
        } else {
            echo "<script>alert('Error updating return status.'); window.location.href='my_customer_order.php';</script>";
        }
    } else {
        echo "<script>alert('Rental record not found.'); window.location.href='my_customer_order.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='my_customer_order.php';</script>";
}
?>
