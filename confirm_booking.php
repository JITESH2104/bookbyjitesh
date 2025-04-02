<?php
// ✅ Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'C:\\xampp\\htdocs\\book\\database_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\book\vendor\autoload.php';


// ✅ Check if token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("<script>alert('Invalid request!'); window.location.href = 'homepage.php';</script>");
}

$token = $_GET['token'];

// ✅ Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $mobile = trim($_POST['mobile']);
    $optional_mobile = trim($_POST['optional_mobile']);
    $return_date = trim($_POST['return_date']);
    $return_time = trim($_POST['return_time']); // ✅ Capture time in AM/PM format

    // ✅ Validate required fields
    if (empty($full_name) || empty($address) || empty($city) || empty($pincode) || empty($mobile) || empty($return_date) || empty($return_time)) {
        die("<script>alert('All required fields must be filled!'); window.history.back();</script>");
    }

    // ✅ Validate return date
    if (!strtotime($return_date)) {
        die("<script>alert('Invalid return date!'); window.history.back();</script>");
    }

    // ✅ Validate return time format (HH:MM AM/PM)
    if (!preg_match('/^(0?[1-9]|1[0-2]):[0-5][0-9] (AM|PM)$/i', $return_time)) {
        die("<script>alert('Invalid time format! Please enter in HH:MM AM/PM format.'); window.history.back();</script>");
    }

    // ✅ Fetch book and owner details
    $query = "SELECT users.email AS owner_email, view_requests.customer_email
              FROM view_requests 
              JOIN books ON view_requests.book_id = books.id 
              JOIN users ON books.user_id = users.id 
              WHERE view_requests.token = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $owner_email = $row['owner_email'];
        $customer_email = $row['customer_email'];

        // ✅ Insert booking details into orders table
        $insertQuery = "INSERT INTO orders (token, customer_email, owner_email, full_name, address, city, pincode, mobile, optional_mobile, return_date, return_time)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param(
            "sssssssssss",
            $token,
            $customer_email,
            $owner_email,
            $full_name,
            $address,
            $city,
            $pincode,
            $mobile,
            $optional_mobile,
            $return_date,
            $return_time
        );
        $insertStmt->execute();
        $insertStmt->close();

        // ✅ Send email notification using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jitupardhi2006@gmail.com'; // ✅ Use your email
            $mail->Password = 'zdtt qqdw askl eqjr';      // ✅ Use app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental Service');
            $mail->addAddress($owner_email);
            $mail->Subject = 'New Book Rental Order - Token: ' . $token;
            $mail->isHTML(true);

            $mail->Body = "
                <p>Hello,</p>
                <p>A customer has confirmed their details for book rental. Below are the details:</p>
                <p><strong>Full Name:</strong> $full_name</p>
                <p><strong>Address:</strong> $address</p>
                <p><strong>City:</strong> $city</p>
                <p><strong>Pincode:</strong> $pincode</p>
                <p><strong>Mobile:</strong> $mobile</p>
                <p><strong>Optional Mobile:</strong> $optional_mobile</p>
                <p><strong>Return Date:</strong> $return_date</p>
                <p><strong>Return Time:</strong> $return_time</p>
                <p><strong>Order Token:</strong> $token</p>
                <p><a href='http://localhost/book/dispatch.php?token=$token' 
                      style='display: inline-block; padding: 10px 20px; font-size: 16px; color: white; background-color: #28a745; 
                             text-decoration: none; border-radius: 5px;'>
                      Proceed to Dispatch
                   </a></p>
                <p>Thank you!</p>
            ";

            $mail->send();
            echo "<script>
                    alert('✅ Booking confirmed! Order details have been saved and emailed to the book owner.');
                    window.location.href = 'homepage.php';
                  </script>";
        } catch (Exception $e) {
            die("<script>alert('❌ Error sending email: " . addslashes($mail->ErrorInfo) . "');</script>");
        }
    } else {
        die("<script>alert('❌ Invalid token or book owner not found!'); window.location.href = 'homepage.php';</script>");
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Customer Details</title>

    <!-- ✅ Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f7e9de;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
        }
        .btn-block {
            margin-top: 20px;
            width: 100%;
        }
        .flatpickr-input {
            background-color: #fff;
            border: 1px solid #ced4da;
            padding: 8px;
            width: 100%;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Enter Customer Details</h2>
        <form action="confirm_booking.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Full Address:</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label>City:</label>
                <input type="text" name="city" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Pincode:</label>
                <input type="text" name="pincode" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mobile Number:</label>
                <input type="text" name="mobile" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Optional Mobile Number (if any):</label>
                <input type="text" name="optional_mobile" class="form-control">
            </div>
            <div class="form-group">
                <label>Return Date:</label>
                <input type="text" id="return_date" name="return_date" class="form-control flatpickr" placeholder="Select Return Date" required>
            </div>
            <div class="form-group">
                <label>Return Time:</label>
                <input type="text" id="return_time" name="return_time" class="form-control flatpickr" placeholder="Select Return Time" required>
            </div>
            
            <button type="submit" class="btn btn-success btn-block">Confirm & Proceed</button>
        </form>
    </div>

    <!-- ✅ Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // ✅ Initialize Flatpickr for Date
        flatpickr("#return_date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: true
        });

        // ✅ Initialize Flatpickr for Time with 12-hour format
        flatpickr("#return_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false,
            disableMobile: true
        });
    </script>
</body>
</html>
