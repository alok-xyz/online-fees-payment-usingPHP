<?php
session_start();
require 'vendor/autoload.php';
require 'razorpay-php/Razorpay.php';

use Razorpay\Api\Api;

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'lms_payment_system');

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Razorpay API credentials
$keyId = 'your_key'; 
$keySecret = 'your_key'; 

// Amount in INR (change according to your requirement)
$amount = 10000; // This is 100.00 INR in paise (Razorpay works in paise)

// Create an Order with Razorpay
$api = new Api($keyId, $keySecret);
$orderData = [
    'receipt' => strval(time()), // Cast receipt to string
    'amount' => $amount, // Amount is in paise
    'currency' => 'INR', // Currency
];

try {
    $order = $api->order->create($orderData); // Create order in Razorpay
    $orderId = $order->id; // Store the order ID

    // Save the order to the database
    $stmt = $conn->prepare("INSERT INTO payments (user_id, order_id, amount, status) VALUES (?, ?, ?, 'pending')");
    
    // Get the logged-in user's ID from session
    $user_id = $_SESSION['user_id']; 

    // Bind parameters and execute the statement
    $stmt->bind_param("isi", $user_id, $orderId, $amount);
    $stmt->execute();

    echo "<script src='https://checkout.razorpay.com/v1/checkout.js'></script>"; // Include Razorpay Checkout

    // Redirect to Razorpay checkout page
    echo "<script>
        var options = {
            key: '$keyId',
            amount: $amount,
            currency: 'INR',
            name: 'Admit Card Fees',
            description: 'This is your current semester admit fee',
            order_id: '$orderId',
            handler: function (response) {
                // Redirect to verify.php with payment details
                var paymentId = response.razorpay_payment_id;
                var orderId = response.razorpay_order_id;
                var signature = response.razorpay_signature;

                // Redirecting with payment details
                window.location.href = 'verify.php?payment_id=' + paymentId + '&order_id=' + orderId + '&signature=' + signature;
            },
            prefill: {
                name: 'Your Name',
                email: 'email@example.com',
                contact: '9999999999'
            },
            theme: {
                color: '#33ffbe'
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    </script>";
} catch (Exception $e) {
    echo "Error creating order: " . $e->getMessage();
}

?>
