<?php
session_start();
require 'vendor/autoload.php';
include 'C:\\xampp\\htdocs\\book\\database_connection.php';

use Razorpay\Api\Api;

// Razorpay API Credentials
$api_key = 'your_api_key';
$api_secret = 'your_api_secret';

// Initialize Razorpay API
$api = new Api($api_key, $api_secret);

$deposit_amount = 500 * 100; // ₹500 in paise
$receipt_id = "order_" . time();

// ✅ Step 1: Create Razorpay Order
$order = $api->order->create([
    'amount' => $deposit_amount,
    'currency' => 'INR',
    'receipt' => $receipt_id,
    'payment_capture' => 1
]);

$order_id = $order['id']; // Store order ID

// ✅ Step 2: Store Order in Database (Optional)
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("INSERT INTO orders (user_id, razorpay_order_id, deposit_amount, payment_status) VALUES (?, ?, ?, 'pending')");
    $stmt->bind_param("isi", $user_id, $order_id, $deposit_amount);
    $stmt->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Refundable Deposit</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<h2>Pay ₹500 Refundable Deposit</h2>
<button id="pay-btn">Pay Now</button>

<script>
document.getElementById('pay-btn').addEventListener('click', function() {
    var options = {
        "key": "<?php echo $api_key; ?>",
        "amount": "<?php echo $deposit_amount; ?>",
        "currency":
