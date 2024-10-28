<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'lms_payment_system');

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Get the logged-in user's details
$user_id = $_SESSION['user_id'];
$userQuery = "SELECT * FROM users WHERE id = ?";
$stmtUser = $conn->prepare($userQuery);
$stmtUser->bind_param("i", $user_id);
$stmtUser->execute();
$userResult = $stmtUser->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Get the user's payment information
$paymentQuery = "SELECT * FROM payments WHERE user_id = ?";
$stmtPayment = $conn->prepare($paymentQuery);
$stmtPayment->bind_param("i", $user_id);
$stmtPayment->execute();
$paymentResult = $stmtPayment->get_result();

if ($paymentResult->num_rows > 0) {
    $payment = $paymentResult->fetch_assoc();
    $paymentStatus = $payment['status']; // 'pending' or 'paid'
    $amountInPaisa = $payment['amount']; // Payment amount in paisa
    $amountInRupees = $amountInPaisa / 100; // Convert amount to rupees
    $orderId = $payment['order_id']; // Razorpay Order ID
} else {
    $paymentStatus = 'pending';
    $amountInPaisa = 10000; // Default amount in paisa (150 rupees)
    $amountInRupees = $amountInPaisa / 100;
    $orderId = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            display: flex;
            justify-content: space-between;
            padding: 50px;
        }
        .left-side {
            width: 25%;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .right-side {
            width: 70%;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .tab {
            padding: 10px;
            cursor: pointer;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
        }
        .tab:hover {
            background-color: #e9ecef;
        }
        .active-tab {
            background-color: #007bff;
            color: white;
        }
        .payment-details, .payment-tab {
            display: none;
        }
        .show {
            display: block;
        }
        .logout-button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            float: right;
        }
        .btn-disabled {
            background-color: grey;
            cursor: not-allowed;
        }
        
    </style>
</head>
<body>

<div class="dashboard-container">
    <!-- Left Side Tabs -->
    <div class="left-side">
        <div id="tab1" class="tab active-tab" onclick="showTab('payment-tab')">Payment</div>
        <div id="tab2" class="tab" onclick="showTab('payment-details')">Payment Details</div>
    </div>

    <!-- Right Side Content -->
    <div class="right-side">
        <div class="dashboard-header">
            <h1 class="blockquote">Welcome, <?php echo $user['name']; ?>!</h1>
            <form method="POST" action="logout.php">
                <button type="submit" class="logout-button">Logout</button>
            </form>
        </div>

        <!-- Payment Tab -->
        <div id="payment-tab" class="payment-tab show">
    <!--<h2>Payment</h2>-->
    <?php if ($paymentStatus == 'paid') { ?>
        <p>You have already paid ₹<?php echo number_format($amountInRupees, 2); ?>. Thank you!</p>
        <!-- The button is removed for users who have already paid -->
    <?php } else { ?>
        <p><strong>Amount Due:</strong> ₹<?php echo number_format($amountInRupees, 2); ?></p>
        <p><strong>Status:</strong> <span style="color:red;">Pending</span></p>
        <form action="pay.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
            <button type="submit" class="btn btn-primary">Pay Now</button>
        </form>
    <?php } ?>
</div>


        <!-- Payment Details Tab -->
        <div id="payment-details" class="payment-details">
            <h2>Payment Details</h2>
            <?php if ($paymentStatus == 'paid') { ?>
                <p><strong>Amount Paid:</strong> ₹<?php echo number_format($amountInRupees, 2); ?></p>
                <p><strong>Status:</strong> <span style="color:green;">Paid</span></p>
                <button onclick="window.location.href='receipt.php?order_id=<?php echo $orderId; ?>'" class="btn btn-success">Download Receipt</button>
            <?php } else { ?>
                <p>No payment details available.</p>
            <?php } ?>
        </div>
    </div>
</div>

<script>
        

    function showTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.payment-tab, .payment-details').forEach(function(tab) {
            tab.classList.remove('show');
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.tab').forEach(function(tab) {
            tab.classList.remove('active-tab');
        });

        // Show the selected tab and set it as active
        document.getElementById(tabId).classList.add('show');
        document.getElementById('tab' + (tabId === 'payment-tab' ? '1' : '2')).classList.add('active-tab');
    }
    
</script>

</body>
</html>
