<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SESSION['role'] !== 'employer') {
    header('Location: dashboard.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle select or reject actions
if (isset($_POST['action']) && isset($_POST['applicant_id']) && isset($_POST['job_id'])) {
    $action = $_POST['action'];
    $applicant_id = $_POST['applicant_id'];
    $job_id = $_POST['job_id'];

    // Update applied_job table
    $update_query = "UPDATE applied_job SET select_status = '$action' WHERE applied_by = '$applicant_id' AND id = '$job_id'";
    if ($conn->query($update_query)) {
        // Prepare the notification message
        $job_title = $conn->real_escape_string($_POST['job_title']);
        $company_name = $conn->real_escape_string($_POST['company_name']);
        $notification_message = "You've been " . ($action === 'selected' ? "selected" : "rejected") . " for the job '$job_title' posted by $company_name";

        // Escape the notification message
        $notification_message = $conn->real_escape_string($notification_message);

        // Insert notification into the notifications table
        $notification_query = "INSERT INTO notifications (user_id, message) VALUES ('$applicant_id', '$notification_message')";
        if (!$conn->query($notification_query)) {
            echo "<script>alert('Failed to insert notification.');</script>";
        }

        echo "<script>alert('Applicant status updated successfully.'); window.location.href='applicant.php?job_id=$job_id';</script>";
    } else {
        echo "<script>alert('Failed to update applicant status.');</script>";
    }
}

// Fetch job details
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $job_query = "SELECT * FROM jobs WHERE id = '$job_id'";
    $job_result = $conn->query($job_query);
    if ($job_result && $job_result->num_rows > 0) {
        $job = $job_result->fetch_assoc();
    } else {
        die("Job not found.");
    }

    // Fetch applicants for this job
    $applicants_query = "SELECT applied_job.applied_by, users.id, users.name, users.email, applied_job.select_status
                        FROM applied_job
                        INNER JOIN users ON applied_job.applied_by = users.email
                        WHERE applied_job.id = '$job_id' AND applied_job.del='false'";
    $applicants_result = $conn->query($applicants_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants for <?php echo $job['job_title']; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            color: #333333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            color: #333333;
        }

        table td {
            vertical-align: middle;
        }

        .cv-link {
            background-color: #007bff;
            color: white;
            border: 2px solid #007bff;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .cv-link:hover {
            background-color: #0056b3;
        }

        .select-btn, .reject-btn {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .select-btn {
            background-color: #28a745;
            color: white;
            border: 2px solid #28a745;
        }

        .select-btn:hover {
            background-color: #218838;
        }

        .reject-btn {
            background-color: #dc3545;
            color: white;
            border: 2px solid #dc3545;
        }

        .reject-btn:hover {
            background-color: #c82333;
        }

        .back-button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: fit-content;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>
<main>
    <h2>Applicants for <?php echo $job['job_title']; ?></h2>
    <br><h3 style="color: red;">Note: Once you've taken the action, you cannot change it, so please think carefully before deciding.</h3><br>
    <table>
        <thead>
        <tr>
            <th>Applicant Name</th>
            <th>Email</th>
            <th>CV</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($applicants_result && $applicants_result->num_rows > 0): ?>
            <?php while ($applicant = $applicants_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $applicant['name']; ?></td>
                    <td><?php echo $applicant['email']; ?></td>
                    <td><a class="cv-link" href="generate_cv_pdf.php?cv_id=<?php echo $applicant['email'];?>" target="_blank">View CV</a></td>
                    <td><?php echo ucfirst($applicant['select_status']); ?></td>
                    <td>
                        <?php if ($applicant['select_status'] == "pending"): ?>
                            <form action="" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="selected">
                                <input type="hidden" name="applicant_id" value="<?php echo $applicant['applied_by']; ?>">
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                                <input type="hidden" name="job_title" value="<?php echo $job['job_title']; ?>">
                                <input type="hidden" name="company_name" value="<?php echo $job['company_name']; ?>">
                                <button type="submit" class="select-btn">Select</button>
                            </form>
                            <form action="" method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="rejected">
                                <input type="hidden" name="applicant_id" value="<?php echo $applicant['applied_by']; ?>">
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                                <input type="hidden" name="job_title" value="<?php echo $job['job_title']; ?>">
                                <input type="hidden" name="company_name" value="<?php echo $job['company_name']; ?>">
                                <button type="submit" class="reject-btn">Reject</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No applicants found for this job.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <a href="./your_job.php" class="back-button">Back to Your Job Listings</a>
    <a href="./dashboard.php" class="back-button">Back to Dashboard</a>
</main>
</body>
</html>
