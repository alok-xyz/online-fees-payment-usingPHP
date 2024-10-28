<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'lms_payment_system'); // Update with your database credentials

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch and sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $amount_due = 100; // Default amount

    // Handle captcha
    $captcha_input = isset($_POST['captcha']) ? intval($_POST['captcha']) : 0;
    $captcha_correct = intval($_SESSION['captcha_sum'] ?? 0);

    // Validate captcha
    if ($captcha_input !== $captcha_correct) {
        $error_message = "Incorrect captcha. Please try again.";
    } else {
        // Insert user data into the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
        $insert_query = "INSERT INTO users (name, email, mobile, password, amount_due) 
                         VALUES ('$name', '$email', '$mobile', '$hashed_password', '$amount_due')";

        if (mysqli_query($conn, $insert_query)) {
            // Optionally, redirect to the login page or show a success message
            $_SESSION['success'] = "Registration successful. You can log in now.";
            header("Location: login.php"); // Redirect to login page after successful signup
            exit();
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

// Generate simple number-based captcha
$num1 = rand(0, 9);
$num2 = rand(0, 9);
$_SESSION['captcha_sum'] = $num1 + $num2; // Store the sum in session for verification
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .signup-box {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-box">
            <h2 class="text-center">Signup</h2>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form action="" method="POST" onsubmit="return validateCaptcha()">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="mobile" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Captcha Section -->
                <div class="mb-3">
                    <label for="captcha" class="form-label">Captcha</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Enter the sum" required>
                        <span class="input-group-text" id="captcha-equation"><?php echo "$num1 + $num2"; ?></span>
                    </div>
                    <div id="captcha-error" class="text-danger mt-2" style="display: none;">Incorrect captcha. Try again!</div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Signup</button>
            </form>
            <p class="mt-3 text-center">Alredy have an account? <a href="login.php">Login </a></p>
            <p class="mt-4 text-center"> <a href="index.php">Home</a></p>
        </div>
    </div>

    <script>
        // Validate captcha before submitting the form
        function validateCaptcha() {
            const userInput = parseInt(document.getElementById('captcha').value);
            const correctCaptcha = <?php echo $num1 + $num2; ?>; // Capture the correct answer from PHP
            if (userInput !== correctCaptcha) {
                document.getElementById('captcha-error').style.display = 'block';
                return false;
            }
            return true;
        }
    </script>
    
</body>
</html>
