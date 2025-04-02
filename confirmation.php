<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Request Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .container { max-width: 800px; margin-top: 50px; }
        .card { border: 1px solid #007bff; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">Rental Request Submitted</h3>
            <p class="text-center">Your rental request has been successfully submitted! The book owner will review your request shortly.</p>
            <div class="text-center">
                <a href="homepage.php" class="btn btn-primary">Go to Home</a>
                <a href="profile.php" class="btn btn-outline-primary">Go to Profile</a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
