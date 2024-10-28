<?php
// Include database connection
include('db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch payment details for the user from the database
$sql = "SELECT payments.order_id, users.name, payments.payment_date, payments.amount 
        FROM payments 
        JOIN users ON payments.user_id = users.id 
        WHERE payments.user_id = ? AND payments.status = 'paid' 
        ORDER BY payments.payment_date DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Set variables from the query result
    $transactionId = $row['order_id']; 
    $userName = $row['name']; 
    $paymentDate = date('F j, Y, g:i a', strtotime($row['payment_date']));
    $amountInRupees = $row['amount'] / 100; // Convert paise to rupees (assuming the amount is stored in paise)
} else {
    // Handle case where no payment record exists
    $transactionId = 'N/A';
    $userName = 'Unknown';
    $paymentDate = 'N/A';
    $amountInRupees = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .receipt-box {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .receipt-header {
            background-color: #4CAF50;
            padding: 10px;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .receipt-body {
            padding: 20px;
        }
        .receipt-footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }
        .amount-box {
            background-color: #E3F2FD;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="receipt-box">
        <!-- Header Section -->
        <div class="receipt-header">
            <h2>Payment Receipt</h2>
        </div>

        <!-- Body Section -->
        <div class="receipt-body">
            <p><strong>Transaction ID:</strong> <?php echo $transactionId; ?></p>
            <p><strong>User Name:</strong> <?php echo $userName; ?></p>
            <p><strong>Payment Date:</strong> <?php echo $paymentDate; ?></p>
            <div class="amount-box">
                <p><strong>Amount Paid:</strong> ₹<?php echo number_format($amountInRupees, 2); ?></p>
                <p><strong>Status:</strong> <span class="text-success">Paid</span></p>
                
            </div>
        </div>

        <!-- Footer Section -->
        <div class="receipt-footer">
            <p>Thank you for your payment!</p>
            <p>If you have any questions, feel free to <a href="contact.php">contact us</a>.</p>
        </div>   
    </div>
    <div class="footer">
            <div class="d-grid gap-2 col-2 mx-auto">
                <button id="printButton" class="btn btn-outline-info btn-sm">Print Receipt</button>
            </div>
        </div>
        <p class="mt-3 text-center"> <a href="dashboard.php">Go Back</a></p>
</div>
        

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printButton = document.getElementById('printButton');

            printButton.onclick = function() {
                // Hide the print button immediately
                printButton.style.display = 'none'; // Use style display to hide

                // Trigger the print dialog
                window.print();

                // Show the button again after printing is complete
                window.onafterprint = function() {
                    printButton.style.display = 'inline-block'; // Restore the button
                };
            };
        });
    </script>
</body>
</html>
