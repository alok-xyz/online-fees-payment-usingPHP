<?php
include 'db.php'; // Ensure this file establishes a connection to your database

// Initialize the notices_result variable
$notices_result = [];

// Fetch notices from the database
$notices_query = "SELECT id, link, file_path, created_at FROM notices ORDER BY created_at DESC";
if ($result = $conn->query($notices_query)) {
    $notices_result = $result; // Assign the result set to the variable
} else {
    echo "<div class='alert alert-danger'>Error fetching notices: " . $conn->error . "</div>"; // Print error if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    
    <style>
        body { font-family: Arial, sans-serif; }
        .logo { float: left; margin: 10px; }
        .auth { float: right; margin: 10px; }
        .notice-box { height: 200px; overflow-y: scroll; background-color: #f8f9fa; padding: 15px; border: 1px solid #dee2e6; }
        .blink { animation: blink 1s step-end infinite; color: red; }
        @keyframes blink { 50% { opacity: 0; } }
        .notice-box {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: box-shadow 0.3s;
            background-color: #f8f9fa;
        }
        .notice-section {
    overflow-y: auto;
    max-height: 300px;
    border: 1px solid #ddd;
    padding: 10px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.notice-section h5 {
    font-weight: bold;
    margin-bottom: 15px;
}

.notice-item {
    margin-bottom: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    transition: background-color 0.3s ease;
}

.notice-item:hover {
    background-color: #f0f8ff; /* Light blue on hover */
}

.new-gif {
    width: 30px;
    vertical-align: middle;
    margin-left: 5px;
}

.notice-date {
    font-size: 12px;
    color: gray;
    margin-top: 5px;
}
.notice-item p.text-center {
    margin: 0; /* Remove default margin */
    color: gray; /* Make the text gray for better visibility */
    font-style: italic; /* Optional: italicize the no notices message */
}

    </style>
</head>
<body>

    <!-- Header Section -->
    <header class="d-flex justify-content-between align-items-center p-3">
        <div class="logo">
            <h2>Online Fees Payment</h2>
        </div>
        <div class="auth">
            <a href="signup.php" class="btn btn-primary">Sign Up</a>
            <a href="login.php" class="btn btn-secondary">Login</a>
            <a href="admin_login.php" class="btn btn-danger">ADMIN</a>
        </div>
    </header>
    <hr>
    <!-- Hero Section with Image Slider -->
    <section class="container mt-4">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/img1.jpg" width="500" height="400" class="d-block w-100" alt="Responsive image">
                </div>
                <div class="carousel-item">
                    <img src="img/img2.jpg" width="500" height="400" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="img/img3.jpg" width="500" height="400"  class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Notice Section -->
    <?php
// Fetch notices from the database
$notices_query = "SELECT * FROM notices ORDER BY created_at DESC";
$notices_result = $conn->query($notices_query);

// Create the notice section
echo '<div class="notice-section">';
echo '<h5 style="text-align:center" >Notice Board</h5>';

// Check if there are notices available
if ($notices_result && $notices_result->num_rows > 0) {
    while ($row = $notices_result->fetch_assoc()) {
        $title = $row['title'];
        $created_at = date("F j, Y, g:i a", strtotime($row['created_at']));
        echo '<div class="notice-item">';
        echo '<a href="notice_detail.php?id=' . $row['id'] . '" class="text-decoration-none text-dark">';
        echo '<strong>' . $title . ' <img src="new.gif" alt="New" class="new-gif"/></strong>';
        echo '<p class="notice-date">' . $created_at . '</p>';
        echo '</a>';
        echo '</div>';
    }
} else {
    echo '<div class="notice-item">';
    echo '<p class="text-center">No notices available at the moment.</p>'; // Message when no notices are present
    echo '</div>';
}

echo '</div>';
?>
    <!-- Footer Section -->
    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>&copy; 2024 OFP. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
