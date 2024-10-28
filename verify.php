<?php
session_start();
require 'vendor/autoload.php';

use Razorpay\Api\Api;

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'lms_payment_system');

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

$keyId = 'rzp_test_EDUOmpuSBk5nmX'; 
$keySecret = '9HCPJAx4rDBSP5o1YJL5DBcD';
// Get payment details from the URL
$paymentId = $_GET['payment_id'];
$orderId = $_GET['order_id'];
$signature = $_GET['signature'];

// Create an instance of Razorpay API
$api = new Api($keyId, $keySecret);

try {
    // Verify payment signature
    $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);

    if ($expectedSignature === $signature) {
        // Payment is successful, update the database
        $stmt = $conn->prepare("UPDATE payments SET status = 'paid' WHERE order_id = ?");
        $stmt->bind_param("s", $orderId);
        $stmt->execute();

        // Redirect to success page
        header("Location: success.php");
        exit();
    } else {
        throw new Exception("Payment verification failed.");
    }
} catch (Exception $e) {
    echo "Payment verification failed: " . $e->getMessage();
}
?>
