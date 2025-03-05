<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user email from session
$user_email = $_SESSION['email'];

// Initialize variables for notifications
$new_notifications = [];
$old_notifications = [];

// Fetch new notifications for the user
$new_noti_sql = "SELECT message FROM notifications WHERE user_id = '$user_email' AND status = 'unread'";
$new_noti_result = $conn->query($new_noti_sql);

while ($notification = $new_noti_result->fetch_assoc()) {
    $new_notifications[] = $notification['message'];
}

// Fetch old notifications for the user
$old_noti_sql = "SELECT message FROM notifications WHERE user_id = '$user_email' AND status = 'read' ORDER BY created_at DESC";
$old_noti_result = $conn->query($old_noti_sql);

while ($notification = $old_noti_result->fetch_assoc()) {
    $old_notifications[] = $notification['message'];
}

// Update notifications to read status after fetching new notifications
if (!empty($new_notifications)) {
    $update_noti_stat_sql = "UPDATE notifications SET status = 'read' WHERE user_id = '$user_email' AND status = 'unread'";
    $conn->query($update_noti_stat_sql);
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .back-button { display: inline-block; background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; transition: background-color 0.3s ease; text-align: center; margin: 20px auto; display: block; max-width: 200px; }
        .back-button:hover { background-color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Notifications</h2>

        <!-- New Notifications Section -->
        <h3>New Notifications</h3>
        <?php if (!empty($new_notifications)): ?>
            <table>
                <tr><th>Message</th></tr>
                <?php foreach ($new_notifications as $message): ?>
                    <tr><td><?php echo $message; ?></td></tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No new notifications.</p>
        <?php endif; ?>

        <!-- Old Notifications Section -->
        <h3>Old Notifications</h3>
        <?php if (!empty($old_notifications)): ?>
            <table>
                <tr><th>Message</th></tr>
                <?php foreach ($old_notifications as $message): ?>
                    <tr><td><?php echo $message; ?></td></tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No old notifications.</p>
        <?php endif; ?>

        <a href="./dashboard.php" class="back-button">Back to Dashboard</a>
    </div>
</body>
</html>
