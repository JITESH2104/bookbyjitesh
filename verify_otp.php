<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = trim($_POST["otp"]);

    if (!isset($_SESSION['otp'])) {
        die("Session expired! Request a new OTP.");
    }

    if ($user_otp == $_SESSION['otp']) {
        echo "OTP verified successfully. <a href='reset_password_form.php'>Reset Password</a>";
        unset($_SESSION['otp']);  // Clear OTP after verification
    } else {
        echo "Invalid OTP. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #4c84ff;
            color: white;
            font-weight: bold;
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }
        .otp-input {
            letter-spacing: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            padding-left: 1.5rem;
        }
        .btn-primary {
            background-color: #4c84ff;
            border-color: #4c84ff;
        }
        .btn-primary:hover {
            background-color: #3a70e0;
            border-color: #3a70e0;
        }
        .card-footer {
            background-color: transparent;
            border-top: none;
        }
        .timer {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header text-center py-3">
                        <h4 class="mb-0">Verify Your Account</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="/api/placeholder/100/100" alt="Security Icon" class="mb-3">
                            <p class="mb-1">We've sent a verification code to your email</p>
                            <p class="text-muted small">Please enter the code below to verify your account</p>
                        </div>
                        
                        <form action="" method="POST">
                            <div class="mb-4">
                                <label for="otp" class="form-label">Verification Code</label>
                                <input type="text" class="form-control otp-input" id="otp" name="otp" maxlength="6" required 
                                       placeholder="······" autocomplete="off">
                            </div>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary py-2">Verify OTP</button>
                            </div>
                            
                            <div class="text-center timer">
                                Code expires in: <span id="timer">05:00</span>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <p class="mb-0">Didn't receive the code? <a href="#" class="text-decoration-none">Resend</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Timer Script -->
    <script>
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            const interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                
                display.textContent = minutes + ":" + seconds;
                
                if (--timer < 0) {
                    clearInterval(interval);
                    display.textContent = "Expired";
                    display.parentElement.style.color = "#dc3545";
                }
            }, 1000);
        }
        
        window.onload = function() {
            const fiveMinutes = 60 * 5;
            const display = document.querySelector('#timer');
            startTimer(fiveMinutes, display);
        };
    </script>
</body>
</html>