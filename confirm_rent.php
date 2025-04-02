<?php
session_start();
include 'C:\xampp\htdocs\book\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\book\vendor\autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>
            alert('Invalid request method!');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

if (!isset($_POST['book_id']) || empty($_POST['book_id'])) {
    echo "<script>
            alert('Invalid request! Missing book ID.');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

$book_id = intval($_POST['book_id']);

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    echo "<script>
            alert('User not logged in!');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

$user_email = $_SESSION['email'];

$query = "SELECT books.*, users.email AS owner_email FROM books 
          JOIN users ON books.user_id = users.id 
          WHERE books.id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo "<script>
            alert('Database error: Unable to prepare query.');
            window.location.href = 'homepage.php';
          </script>";
    exit();
}

$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($book = $result->fetch_assoc()) {
    $owner_email = $book['owner_email'];
    $book_title = $book['title'];

    if (!filter_var($owner_email, FILTER_VALIDATE_EMAIL) || !filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Invalid email address.');
                window.location.href = 'homepage.php';
              </script>";
        exit();
    }

    $token = bin2hex(random_bytes(16));
    
    $insertToken = "INSERT INTO view_requests (book_id, customer_email, token, created_at) VALUES (?, ?, ?, NOW())";
    $stmtToken = $conn->prepare($insertToken);
    $stmtToken->bind_param("iss", $book_id, $user_email, $token);
    $stmtToken->execute();

    $customer_info_link = "http://localhost/book/customer_info.php?token=$token";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jitupardhi2006@gmail.com';
        $mail->Password = 'zdtt qqdw askl eqjr'; // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental Service');
        $mail->addAddress($owner_email);
        $mail->Subject = 'Book Rental Request';
        $mail->isHTML(true);
        $mail->Body = "<p>Hello,</p>
                       <p>Someone is interested in renting your book: <strong>$book_title</strong>.</p>
                       <p><strong>User Email:</strong> $user_email</p>
                       <p>To accept or decline the request, click one of the buttons below:</p>
                       <p>
                           <a href='http://localhost/book/handle_request.php?token=$token&status=accepted' 
                              style='padding: 10px 15px; background-color: green; color: white; text-decoration: none; border-radius: 5px;'>Accept</a>
                           <a href='http://localhost/book/handle_request.php?token=$token&status=declined' 
                              style='padding: 10px 15px; background-color: red; color: white; text-decoration: none; border-radius: 5px;'>Decline</a>
                       </p>
                       <p>Thank you!</p>";

        if ($mail->send()) {
            echo "<script>
                    alert('Rental request sent! The book owner has been notified.');
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
                alert('Mailer Error: {$mail->ErrorInfo}');
                window.location.href = 'homepage.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Book not found!');
            window.location.href = 'homepage.php';
          </script>";
}

$stmt->close();
?>
