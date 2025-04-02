<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\vendor\autoload.php';  // Use Composer's autoloader

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Generate a 6-digit OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Send OTP via Email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jitupardhi2006@gmail.com'; // Your Gmail ID
        $mail->Password   = 'zdtt qqdw askl eqjr ';  // Use App Password, not Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('jitupardhi2006@gmail.com', 'Book Rental System');
        $mail->addAddress($email);

        $mail->Subject = 'Password Reset OTP';
        $mail->Body    = "Your OTP for password reset is: $otp";

        $mail->send();
        echo "OTP sent successfully. <a href='verify_otp.php'>Verify OTP</a>";
    } catch (Exception $e) {
        echo "Error sending OTP: " . $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Book Rental System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .card-header {
            background-color: #FF8C00; /* Changed to orange */
            color: white;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            text-align: center;
            padding: 20px;
        }
        .form-control:focus {
            border-color: #FF8C00; /* Changed to orange */
            box-shadow: 0 0 0 0.25rem rgba(255, 140, 0, 0.25); /* Changed to orange */
        }
        .btn-primary {
            background-color: #FF8C00; /* Changed to orange */
            border-color: #FF8C00; /* Changed to orange */
            width: 100%;
            padding: 10px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #E67E00; /* Darker orange for hover */
            border-color: #E67E00; /* Darker orange for hover */
        }
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }
        .book-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: white;
        }
        .success-message {
            display: none;
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        .error-message {
            display: none;
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        a {
            color: #FF8C00; /* Changed to orange */
            text-decoration: none;
        }
        a:hover {
            color: #E67E00; /* Darker orange for hover */
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="form-container">
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-book-half book-icon"></i>
                    <h3>Book Rental System</h3>
                    <p class="mb-0">Password Reset</p>
                </div>
                <div class="card-body p-4">
                    <div id="emailForm">
                        <p class="text-muted mb-4">Enter your email address to receive a one-time password.</p>
                        
                        <form id="sendOtpForm" action="" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                                </div>
                                <div class="form-text">We'll send a 6-digit OTP to this email.</div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">
                                <span id="submitText">Send OTP</span>
                                <span id="loadingSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                        
                        <div id="successMessage" class="success-message mt-3">
                            OTP sent successfully! <a href="verify_otp.php" class="fw-bold">Verify OTP</a>
                        </div>
                        
                        <div id="errorMessage" class="error-message mt-3">
                            Error sending OTP. Please try again.
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-center p-3">
                    <a href="login.php" class="text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <script>
        document.getElementById('sendOtpForm').addEventListener('submit', function(event) {
            // Uncomment the following line in real implementation to prevent default form submission
            // event.preventDefault();
            
            const email = document.getElementById('email').value;
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            
            // Basic email validation
            if (!isValidEmail(email)) {
                errorMessage.textContent = "Please enter a valid email address.";
                errorMessage.style.display = "block";
                successMessage.style.display = "none";
                return false;
            }
            
            // Show loading state
            submitText.textContent = "Sending...";
            loadingSpinner.classList.remove('d-none');
            
            // In a real implementation, you would use AJAX to submit the form without page reload
            // For this demo, we'll simulate a successful response after a delay
            
            // Simulate AJAX (remove this in real implementation)
            setTimeout(function() {
                // This is just for demonstration - in real implementation the PHP script handles the response
                submitText.textContent = "Send OTP";
                loadingSpinner.classList.add('d-none');
                successMessage.style.display = "block";
                errorMessage.style.display = "none";
            }, 1500);
            
            // For demo purposes only - remove in real implementation
            // Return false to prevent form submission for the demo
            // return false;
        });
        
        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
</body>
</html>