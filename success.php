<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .countdown {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-green p-6 rounded-lg shadow-lg text-center">
        <h1 class="text-2xl font-semibold mb-4">Payment Successful</h1>
        <p>Your payment was processed successfully.</p>
        <p>You will be redirected to your dashboard in <span id="countdown" class="countdown">5</span> seconds. Don't close the browser.</p>
    </div>

    <script>
        // Countdown logic
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'dashboard.php'; // Redirect to the user dashboard
            }
        }, 1000);
    </script>
</body>
</html>
