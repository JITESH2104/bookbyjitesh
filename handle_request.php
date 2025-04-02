<?php
session_start();
include 'C:\xampp\htdocs\book\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer Files
require 'C:\xampp\htdocs\book\vendor\autoload.php';
;

// ✅ Validate request parameters
if (!isset($_GET['token']) || empty($_GET['token']) || !isset($_GET['status'])) {
    echo "<script>
            alert('Invalid request! Missing token or status.');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

$token = $_GET['token'];
$status = $_GET['status'];

// ✅ Validate status value
if (!in_array($status, ['accepted', 'declined'])) {
    echo "<script>
            alert('Invalid status value!');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

// ✅ Fetch request details from view_requests
$query = "SELECT vr.*, b.id AS book_id, b.title, b.availability_status 
          FROM view_requests vr
          JOIN books b ON vr.book_id = b.id
          WHERE vr.token = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo "<script>
            alert('Database error: Unable to prepare query.');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// ✅ Check if the request is valid
if ($request = $result->fetch_assoc()) {
    $user_email = $request['customer_email'];
    $book_id = $request['book_id'];
    $book_title = $request['title'];

    // ✅ Convert status for better readability
    $statusText = ($status == 'accepted') ? 'Accepted' : 'Declined';

    // ✅ Update request status in view_requests
    $updateQuery = "UPDATE view_requests SET status = ? WHERE token = ?";
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param("ss", $statusText, $token);
    $stmtUpdate->execute();

    // ✅ If request is accepted, update availability_status in books table
    if ($status == 'accepted') {
        $updateBookQuery = "UPDATE books SET availability_status = 'unavailable' WHERE id = ?";
        $stmtBook = $conn->prepare($updateBookQuery);
        $stmtBook->bind_param("i", $book_id);
        $stmtBook->execute();
    }

    // ✅ Send email to the customer
    $mail = new PHPMailer(true);
    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jitupardhi2006@gmail.com';  // Your email
        $mail->Password = 'zdtt qqdw askl eqjr';       // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental Service');
        $mail->addAddress($user_email);

        // ✅ Email subject and content
        $mail->isHTML(true);
        $mail->Subject = 'Your Book Rental Request';

        // ✅ Email content based on status
        if ($status == 'accepted') {
            $mail->Body = "
                <p>Hello,</p>
                <p>Your request for the book <strong>$book_title</strong> has been <strong>Accepted</strong> by the owner.</p>
                <p><a href='http://localhost/book/confirm_booking.php?token=$token' 
                      style='padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px;'>
                      ✅ Proceed to Confirm Booking
                  </a>
                </p>
                <p>Thank you for using our service!</p>";
        } else {
            $mail->Body = "
                <p>Hello,</p>
                <p>Unfortunately, your request for the book <strong>$book_title</strong> has been <strong>Declined</strong> by the owner.</p>
                <p>Thank you!</p>";
        }

        // ✅ Send the email
        if ($mail->send()) {
            echo "<script>
                    alert('Response sent to the user successfully!');
                    window.location.href = 'homepage.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Email could not be sent. Please try again later.');
                    window.location.href = 'homepage.php';
                  </script>";
        }
    } catch (Exception $e) {
        echo "<script>
                alert('Mailer Error: " . addslashes($mail->ErrorInfo) . "');
                window.location.href = 'homepage.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid or expired token!');
            window.location.href = 'homepage.php';
          </script>";
}

// ✅ Close connections
$stmt->close();
$conn->close();
?>
