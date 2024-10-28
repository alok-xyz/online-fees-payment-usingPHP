<?php
// Include database connection
require 'db.php'; // Make sure this file connects to your database

// Check if notice ID is provided
if (isset($_GET['id'])) {
    $notice_id = $_GET['id'];

    // Fetch notice details from the database
    $stmt = $conn->prepare("SELECT * FROM notices WHERE id = ?");
    $stmt->bind_param("i", $notice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $notice = $result->fetch_assoc();
    } else {
        echo "<h2>Notice not found.</h2>";
        exit;
    }
} else {
    echo "<h2>Invalid request.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Notice Details</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .notice-box {
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="notice-box">
            <h2><?php echo htmlspecialchars($notice['title']); ?></h2>
            <p><strong>Uploaded on:</strong> <?php echo date('Y-m-d H:i:s', strtotime($notice['created_at'])); ?></p>
            <hr>
            <?php if (!empty($notice['file_path'])): ?>
                <p><strong>Uploaded File:</strong></p>
                <a href="<?php echo $notice['file_path']; ?>" target="_blank" class="btn btn-info">View File</a>
            <?php endif; ?>
            <?php if (!empty($notice['link'])): ?>
                <p><strong>Notice Link:</strong></p>
                <a href="<?php echo $notice['link']; ?>" target="_blank" class="btn btn-primary">Visit Link</a>
            <?php endif; ?>
            <hr>
            <a href="index.php" class="btn btn-secondary btn-back">Back to Notices</a>
        </div>
    </div>
</body>
</html>
