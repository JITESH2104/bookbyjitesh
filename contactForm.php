<?php
session_start();
include 'C:\xampp\htdocs\book\database_connection.php'; // Database connection

// Debugging: Check if session is set
if (!isset($_SESSION['id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['id']; // Ensure this matches your user table ID

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $date = date('Y-m-d H:i:s');

    // Check if user exists in the database
    $checkUserQuery = "SELECT id FROM user WHERE id = '$user_id'";
    $result = mysqli_query($conn, $checkUserQuery);
    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('User does not exist. Please log in again.'); window.location.href='login.php';</script>";
        exit;
    }

    // Insert message
    $query = "INSERT INTO admin_message (user_id, name, email, message, message_date) 
              VALUES ('$user_id', '$name', '$email', '$message', '$date')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Message sent successfully!'); window.location.href='contactForm.php';</script>";
    } else {
        echo "<script>alert('Error sending message: " . mysqli_error($conn) . "'); window.location.href='contactForm.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-orange: #ff7518;
            --secondary-orange: #ff8c42;
            --dark-orange: #e05f00;
            --light-orange: #ffe6d5;
        }
        
        body {
            background-color: #f9f6f2;
            padding-top: 50px;
        }
        
        .contact-form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 117, 24, 0.15);
            padding: 30px;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-orange);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
            color: #3a3a3a;
        }
        
        .form-header h2 {
            font-weight: 600;
            color: var(--dark-orange);
        }
        
        .form-header p {
            color: #666;
        }
        
        .contact-form-container form {
            margin-top: 20px;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-control:focus {
            border-color: var(--secondary-orange);
            box-shadow: 0 0 0 0.25rem rgba(255, 117, 24, 0.25);
        }
        
        .btn-primary {
            background-color: var(--primary-orange);
            border: none;
            padding: 12px 30px;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            background-color: var(--dark-orange);
            transform: translateY(-2px);
            transition: all 0.3s;
        }
        
        .contact-icon {
            font-size: 24px;
            margin-right: 10px;
            color: var(--primary-orange);
        }
        
        .contact-info {
            margin-top: 20px;
        }
        
        .contact-info-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .contact-info-item p {
            margin-bottom: 0;
        }
        
        .form-header h3 {
            color: var(--dark-orange);
        }
        
        /* Orange accents */
        .orange-accent {
            height: 4px;
            width: 60px;
            background-color: var(--primary-orange);
            margin: 0 auto 20px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="contact-form-container">
                    <div class="form-header">
                        <h2>Contact Us</h2>
                        <div class="orange-accent"></div>
                        <p>We'd love to hear from you! Please fill out the form below.</p>
                    </div>
                    
                    <form action="contactForm.php" method="POST">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                            <label for="name">Your Name</label>
                        </div>
                        
                        <div class="form-floating">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                            <label for="email">Your Email</label>
                        </div>
                        
                        <div class="form-floating">
                            <textarea class="form-control" id="message" name="message" placeholder="Your Message" style="height: 150px" required></textarea>
                            <label for="message">Your Message</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="contact-form-container">
                    <div class="form-header">
                        <h3>Other Ways to Connect</h3>
                        <div class="orange-accent"></div>
                    </div>
                    
                    <div class="contact-info">
                        <div class="contact-info-item">
                            <i class="contact-icon">📍</i>
                            <p>123 book Rental Office Dhule</p>
                        </div>
                        
                        <div class="contact-info-item">
                            <i class="contact-icon">📱</i>
                            <p>+919309276772</p>
                        </div>
                        
                        <div class="contact-info-item">
                            <i class="contact-icon">✉️</i>
                            <p>support@bookstore.com</p>
                        </div>
                        
                        <div class="contact-info-item">
                            <i class="contact-icon">🕒</i>
                            <p>Monday - Friday: 9am - 5pm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>