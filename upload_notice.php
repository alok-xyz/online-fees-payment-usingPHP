<?php
// Database connection
$servername = "localhost"; // Adjust if needed
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "lms_payment_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $noticeTitle = $_POST['notice_title'] ?? ''; // Get notice title
    $noticeLink = $_POST['notice_link'] ?? ''; // Get optional notice link

    $uploadDir = 'uploads/'; // Directory to store uploaded files
    $fileName = $_FILES['notice_file']['name'];
    $fileTmpPath = $_FILES['notice_file']['tmp_name'];

    // Check if file was uploaded
    if (is_uploaded_file($fileTmpPath) && !empty($noticeTitle)) {
        // Sanitize the file name
        $fileName = preg_replace("/[^a-zA-Z0-9.]/", "_", $fileName);
        $destPath = $uploadDir . $fileName;

        // Move the file to the upload directory
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Prepare SQL statement to insert notice into the database
            $stmt = $conn->prepare("INSERT INTO notices (title, file_path, link) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $noticeTitle, $destPath, $noticeLink);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to admin dashboard or a success page
                header("Location: admin_dashboard.php?status=success");
                exit();
            } else {
                echo "Database Error: " . $stmt->error;
            }
        } else {
            echo "Error moving the uploaded file.";
        }
    } else {
        echo "No file uploaded or notice title is empty.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Notice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Upload Notice</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="notice_title" class="form-label">Notice Title</label>
            <input type="text" class="form-control" id="notice_title" name="notice_title" required>
        </div>
        <div class="mb-3">
            <label for="notice_file" class="form-label">Upload File</label>
            <input type="file" class="form-control" id="notice_file" name="notice_file" accept=".pdf,.doc,.docx,.jpg,.png" required>
        </div>
        <div class="mb-3">
            <label for="notice_link" class="form-label">Optional Notice Link</label>
            <input type="url" class="form-control" id="notice_link" name="notice_link" placeholder="https://example.com">
        </div>
        <button type="submit" class="btn btn-primary">Upload Notice</button>
    </form>
</div>
</body>
</html>
