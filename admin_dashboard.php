<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}
include 'db.php';

// Fetch payment records with date and time
$query = "SELECT users.name, users.email, users.mobile, payments.amount, payments.payment_date FROM users INNER JOIN payments ON users.id = payments.user_id WHERE payments.status = 'paid'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .tab-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link {
            border: none;
            font-weight: bold;
            color: #007bff;
        }
        .nav-tabs .nav-link.active {
            border-bottom: 2px solid #007bff;
            color: #495057;
        }
        .logout-btn {
            float: right;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Admin Dashboard</h2>
    <a href="admin_logout.php" class="btn btn-danger logout-btn">Logout</a>
    <div class="row">
        <div class="col-md-3">
            <ul class="nav nav-tabs flex-column">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#paid-status">Paid Status</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#notice">Notice</a>
                </li>
                
            </ul>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div id="paid-status" class="tab-pane fade show active">
                    <h4>Users Who Paid</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Amount (₹)</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                    <td><?php echo number_format($row['amount'] / 100, 2); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($row['payment_date'])); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
           
                <!-- Admin Dashboard Notice Tab -->
                
            <hr>
            <div id="notice" class="tab-pane fade">
    <h3>Notice Board</h3>
    <form action="upload_notice.php" method="POST" enctype="multipart/form-data">
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
    <hr>
    <h4>Uploaded Notices</h4>
    <?php
    // Fetch notices from the database
    $notices_result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");

    if (!$notices_result) {
        // Handle query error
        echo "Error fetching notices: " . $conn->error;
        $notices_result = []; // Initialize as empty array to avoid undefined variable warning
    } else {
        // Check if there are no results
        if ($notices_result->num_rows === 0) {
            echo "<p>No notices uploaded yet.</p>";
        } else {
            echo '<ul class="list-group">';
            while ($row = $notices_result->fetch_assoc()) {
                echo '<li class="list-group-item">';
                echo '<strong>' . htmlspecialchars($row['title']) . '</strong><br>';
                echo '<small>Uploaded on: ' . date('Y-m-d H:i:s', strtotime($row['created_at'])) . '</small><br>';
                if (!empty($row['file_path'])) {
                    echo '<a href="' . $row['file_path'] . '" target="_blank" class="btn btn-info btn-sm">View File</a>';
                }
                if (!empty($row['link'])) {
                    echo '<a href="' . $row['link'] . '" target="_blank" class="btn btn-link btn-sm">Link</a>';
                }
                echo '</li>';
            }
            echo '</ul>';
        }
    }
    ?>
</div>
              
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Disable back and forward navigation
    history.pushState(null, document.title, location.href);
    window.addEventListener('popstate', function (event) {
        history.pushState(null, document.title, location.href);
    });
</script>

</body>
</html>
