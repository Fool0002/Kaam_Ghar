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

// Handle deletion of job
if (isset($_POST['delete_job_id'])) {
    $job_id = $_POST['delete_job_id'];
    $delete_query = "UPDATE jobs SET del = 'true' WHERE id = '$job_id'";
    if ($conn->query($delete_query)) {
        echo "<script>alert('Job deleted successfully.'); window.location.href='your_job.php';</script>";
    } else {
        echo "<script>alert('Failed to delete the job.');</script>";
    }
}

// Fetch jobs posted by employer
$user_id = $_SESSION['email'];
$jobs_query = "SELECT * FROM jobs WHERE email = '$user_id' AND del = 'false'";
$jobs_result = $conn->query($jobs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Job Listings</title>
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

        table td a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
            margin-right: 5px;
            transition: background-color 0.3s ease;
        }

        #view {
            background-color: lime;
            color: black;
            border: 2px solid black;
        }

        #view:hover {
            color: brown;
        }

        table td a:hover {
            background-color: #0056b3;
        }

        table td a.edit-btn {
            background-color: #ffc107;
            color: black;
            border: 2px solid black;
        }

        table td a.edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn {
            background-color: #dc3545;
            text-decoration: none;
            padding: 5px 5px;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
            margin-right: 5px;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #bb2d3b;
        }

        .applicants-link {
            background-color: #007bff;
            color: white;
            border: 2px solid #007bff;
        }

        .applicants-link:hover {
            background-color: #0056b3;
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
            display: block;
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
    <h2>Your Job Listings</h2>
    <?php if ($jobs_result && $jobs_result->num_rows > 0): ?>
        <table>
            <thead>
            <tr>
                <th>Job Title</th>
                <th>Company</th>
                <th>Applicants</th>
                <th>Status</th>
                <th>Actions</th>
                
            </tr>
            </thead>
            <tbody>
            <?php while ($job = $jobs_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $job['job_title']; ?></td>
                    <td><?php echo $job['company_name']; ?></td>
                    <td>
                        <a href="./applicant.php?job_id=<?php echo $job['id']; ?>" class="applicants-link">View Applicants</a>
                    </td>
                    <td><?php echo $job['status']; ?></td>
                    <td>
                        <a href="./job_details.php?job_id=<?php echo $job['id']; ?>" target="_blank" id="view">View Job</a>
                        <?php if($job['status']=="approved"){
                            $jj = $job['id'];
                            echo '<a href="./edit_job.php?job_id=' . $jj . '" class="edit-btn" >Edit</a>';
                        }
                        else
                        {
                            echo '<a class="edit-btn" style = "background-color: gray;color: white;" onclick="alert(\'Rejected jobs cannot be edited\'); return false;">Edit</a>';
                        }
                        ?>
                        <form action="" method="POST" style="display: inline;">
                            <input type="hidden" name="delete_job_id" value="<?php echo $job['id']; ?>">
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this job?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No jobs posted yet.</p>
    <?php endif; ?>
    <a href="./dashboard.php" class="back-button">Back to Dashboard</a>
</main>
</body>
</html>
