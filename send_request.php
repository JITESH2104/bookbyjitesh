<?php
session_start();
include 'C:\xampp\htdocs\book\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\vendor\PHPMailer\phpmailer\src\Exception.php';
require 'C:\xampp\vendor\PHPMailer\phpmailer\src\PHPMailer.php';
require 'C:\xampp\vendor\PHPMailer\phpmailer\src\SMTP.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['book_id'])) {
    exit("Invalid request!");
}

$book_id = intval($_POST['book_id']);
$user_email = $_SESSION['email'] ?? '';

if (!$user_email) {
    exit("User not logged in!");
}

$query = "SELECT books.title, users.email AS owner_email FROM books 
          JOIN users ON books.user_id = users.id 
          WHERE books.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($book = $result->fetch_assoc()) {
    $owner_email = $book['owner_email'];
    $book_title = $book['title'];
    $token = bin2hex(random_bytes(16));

    $insertToken = "INSERT INTO view_requests (book_id, user_email, token, created_at) VALUES (?, ?, ?, NOW())";
    $stmtToken = $conn->prepare($insertToken);
    $stmtToken->bind_param("iss", $book_id, $user_email, $token);
    $stmtToken->execute();

    $accept_link = "http://localhost/book/handle_response.php?token=$token&status=accepted";
    $decline_link = "http://localhost/book/handle_response.php?token=$token&status=declined";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jitupardhi2006@gmail.com';
        $mail->Password = 'zdtt qqdw askl eqjr'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental Service');
        $mail->addAddress($owner_email);
        $mail->Subject = 'Book Rental Request';
        $mail->isHTML(true);
        $mail->Body = "<p>Hello,</p>
                       <p>Someone is interested in renting your book: <strong>$book_title</strong>.</p>
                       <p><strong>User Email:</strong> $user_email</p>
                       <p>
                           <a href='$accept_link' style='padding: 10px 15px; background-color: green; color: white; text-decoration: none; border-radius: 5px;'>Accept</a>
                           <a href='$decline_link' style='padding: 10px 15px; background-color: red; color: white; text-decoration: none; border-radius: 5px;'>Decline</a>
                       </p>
                       <p>Thank you!</p>";

        $mail->send();
        echo "<div class='alert alert-success text-center'>Rental request sent to the book owner.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger text-center'>Mailer Error: {$mail->ErrorInfo}</div>";
    }
} else {
    echo "<div class='alert alert-danger text-center'>Book not found!</div>";
}

$stmt->close();
?>
