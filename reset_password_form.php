<?php
session_start();
if (!isset($_SESSION['email'])) {
    die("Unauthorized access.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            max-width: 450px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-header {
            background-color: #ff7b00;
            color: white;
            font-weight: bold;
            border-radius: 0.375rem 0.375rem 0 0 !important;
        }
        .btn-primary {
            background-color: #ff7b00;
            border-color: #ff7b00;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #e56e00;
            border-color: #e56e00;
        }
        .form-control:focus {
            border-color: #ffa94d;
            box-shadow: 0 0 0 0.25rem rgba(255, 123, 0, 0.25);
        }
        .password-strength {
            height: 5px;
            border-radius: 5px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }
        .text-orange {
            color: #ff7b00;
        }
        .form-floating label {
            color: #6c757d;
        }
        .password-requirements li {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .check-circle {
            color: #28a745;
            display: none;
        }
        .form-icon {
            position: absolute;
            right: 10px;
            top: 13px;
            cursor: pointer;
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
                        <h4 class="mb-0">Reset Your Password</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <img src="/api/placeholder/80/80" alt="Lock Icon" class="mb-3">
                            <p class="mb-1">Create a new password for your account</p>
                            <p class="text-muted small">Make sure it's strong and secure</p>
                        </div>
                        
                        <form action="update_password.php" method="post" id="passwordResetForm">
                            <div class="mb-3 position-relative">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                                    <label for="new_password">New Password</label>
                                    <span class="form-icon" id="togglePassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="password-strength bg-secondary opacity-25" id="password-strength"></div>
                                <small class="text-muted d-block mt-1" id="password-strength-text">Password strength: Too weak</small>
                            </div>
                            
                            <div class="mb-4 position-relative">
                                <div class="form-floating">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                                    <label for="confirm_password">Confirm Password</label>
                                    <span class="form-icon" id="toggleConfirmPassword">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                        </svg>
                                    </span>
                                </div>
                                <small class="text-danger d-none" id="password-match-error">Passwords do not match</small>
                            </div>
                            
                            <div class="mb-4">
                                <p class="mb-1 small fw-bold">Password requirements:</p>
                                <ul class="password-requirements ps-3">
                                    <li id="length-check">At least 8 characters long</li>
                                    <li id="uppercase-check">Contains uppercase letter</li>
                                    <li id="lowercase-check">Contains lowercase letter</li>
                                    <li id="number-check">Contains a number</li>
                                    <li id="special-check">Contains a special character</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-primary py-2">Reset Password</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 bg-white">
                        <p class="mb-0 small">Already remember your password? <a href="login.php" class="text-decoration-none text-orange">Sign in</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Password Strength and Validation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            const passwordStrength = document.getElementById('password-strength');
            const passwordStrengthText = document.getElementById('password-strength-text');
            const passwordMatchError = document.getElementById('password-match-error');
            const form = document.getElementById('passwordResetForm');
            
            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                togglePasswordVisibility(newPassword, this);
            });
            
            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                togglePasswordVisibility(confirmPassword, this);
            });
            
            function togglePasswordVisibility(input, icon) {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                // Change the icon
                if (type === 'text') {
                    icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16"><path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/><path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/></svg>';
                } else {
                    icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>';
                }
            }
            
            // Check password strength
            newPassword.addEventListener('input', function() {
                const password = this.value;
                
                // Update requirement checks
                document.getElementById('length-check').innerHTML = 
                    password.length >= 8 ? 
                    '<span class="text-success">✓ At least 8 characters long</span>' : 
                    'At least 8 characters long';
                
                document.getElementById('uppercase-check').innerHTML = 
                    /[A-Z]/.test(password) ? 
                    '<span class="text-success">✓ Contains uppercase letter</span>' : 
                    'Contains uppercase letter';
                
                document.getElementById('lowercase-check').innerHTML = 
                    /[a-z]/.test(password) ? 
                    '<span class="text-success">✓ Contains lowercase letter</span>' : 
                    'Contains lowercase letter';
                
                document.getElementById('number-check').innerHTML = 
                    /[0-9]/.test(password) ? 
                    '<span class="text-success">✓ Contains a number</span>' : 
                    'Contains a number';
                
                document.getElementById('special-check').innerHTML = 
                    /[^A-Za-z0-9]/.test(password) ? 
                    '<span class="text-success">✓ Contains a special character</span>' : 
                    'Contains a special character';
                
                // Calculate strength
                let strength = 0;
                if (password.length >= 8) strength += 1;
                if (password.length >= 12) strength += 1;
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[a-z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                
                // Update strength indicator
                if (password.length === 0) {
                    passwordStrength.style.width = "0%";
                    passwordStrength.className = "password-strength bg-secondary opacity-25";
                    passwordStrengthText.textContent = "Password strength: Too weak";
                    passwordStrengthText.className = "text-muted d-block mt-1";
                } else if (strength < 3) {
                    passwordStrength.style.width = "25%";
                    passwordStrength.className = "password-strength bg-danger";
                    passwordStrengthText.textContent = "Password strength: Weak";
                    passwordStrengthText.className = "text-danger d-block mt-1";
                } else if (strength < 5) {
                    passwordStrength.style.width = "50%";
                    passwordStrength.className = "password-strength bg-warning";
                    passwordStrengthText.textContent = "Password strength: Medium";
                    passwordStrengthText.className = "text-warning d-block mt-1";
                } else if (strength < 6) {
                    passwordStrength.style.width = "75%";
                    passwordStrength.className = "password-strength bg-info";
                    passwordStrengthText.textContent = "Password strength: Strong";
                    passwordStrengthText.className = "text-info d-block mt-1";
                } else {
                    passwordStrength.style.width = "100%";
                    passwordStrength.className = "password-strength bg-success";
                    passwordStrengthText.textContent = "Password strength: Very strong";
                    passwordStrengthText.className = "text-success d-block mt-1";
                }
                
                // Check if passwords match
                checkPasswordsMatch();
            });
            
            // Check if passwords match
            confirmPassword.addEventListener('input', checkPasswordsMatch);
            
            function checkPasswordsMatch() {
                if (confirmPassword.value && confirmPassword.value !== newPassword.value) {
                    passwordMatchError.classList.remove('d-none');
                } else {
                    passwordMatchError.classList.add('d-none');
                }
            }
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (newPassword.value !== confirmPassword.value) {
                    e.preventDefault();
                    passwordMatchError.classList.remove('d-none');
                }
            });
        });
    </script>
</body>
</html>