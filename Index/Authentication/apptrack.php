<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
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

// Fetch user's job applications
$user_id = $_SESSION['user_id'];
$sql = "SELECT applied_job.id as id, jobs.job_title, applied_job.status, applied_job.employer_email, applied_job.select_status, applied_job.id as job_id, jobs.contact_information 
FROM applied_job 
JOIN jobs ON applied_job.id = jobs.id 
WHERE applied_job.applied_by = '" . $_SESSION['email'] . "' AND applied_job.del = 'false' AND jobs.del = 'false'
";
$result = $conn->query($sql);

$applications = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $job_id = $row['id'];
        $consql = "SELECT contact_information FROM jobs WHERE id = '$job_id'";
        $conresult = $conn->query($consql);
        if ($conresult->num_rows > 0) {
            $contact_info = $conresult->fetch_assoc()['contact_information'];
            $row['contact_information'] = $contact_info;
        } else {
            $row['contact_information'] = '-';
        }
        $applications[] = $row;
    }
}

// Handle cancellation of application
if (isset($_POST['cancel_application_id'])) {
    $application_id = $_POST['cancel_application_id'];
    $delete_query = "UPDATE applied_job SET del = 'true', status = 'pending' WHERE id = '$application_id'";
    if ($conn->query($delete_query)) {
        echo "<script>alert('Application cancelled successfully.'); window.location.href='apptrack.php';</script>";
    } else {
        echo "<script>alert('Failed to cancel the application.');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Applications</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: white;
            padding: 10px 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
        }

        .nav-button {
            background-color: white;
            color: black;
            border: 1px solid black;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .nav-button:hover {
            background-color: gray;
            color: #ffffff;
        }

        .table-container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .action-button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        td a {
            padding: 5px 10px;
            border: 1px solid black;
            border-radius: 5px;
            color: black;
            text-decoration: none;
            background-color: lime;
            cursor: pointer;
        }
        td a:hover {
            color: white;
        }

        .cancel-button {
            background-color: red;
        }

        .cancel-button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
<header>
    <div class="navbar">
        <h3><a href="./dashboard.php" style="text-decoration: none; color:black;">KaamGhar</a></h3>
        <div class="nav-links">
            <a class="nav-button" href="profile.php">Profile</a>
            <a class="nav-button" href="logout.php">Logout</a>
        </div>
    </div>
</header>
<main>
    <Strong><h3 style= "color: red;">Important &nbsp;:&nbsp; Selected jobs will show email and contact number of the employer. Please contact the employer through those means to communicate regarding the job.</h3></Strong>
  
    <div class="table-container">
        <h2>Your Job Applications</h2>
        <?php if (count($applications) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Contact Email</th>
                        <th>Contact Number</th>
                        <th>Selection Status</th>
                        <th>View Job</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?php echo $application['job_title']; ?></td>
                            <td><?php echo $application['status']; ?></td>
                            <td><?php echo ($application['select_status'] == "selected") ? $application['employer_email'] : "-"; ?></td>
                            <td><?php echo ($application['select_status'] == "selected") ? $application['contact_information'] : "-"; ?></td>
                            <td><?php echo $application['select_status']; ?></td>
                            <td><a href="./job_details.php?job_id=<?php echo $application['id']; ?>">View</a></td></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to cancel this application? You cannot apply again after canceling.');">
                                    <input type="hidden" name="cancel_application_id" value="<?php echo $application['id']; ?>">
                                    <button type="submit" class="action-button cancel-button">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No applications found.</p>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
