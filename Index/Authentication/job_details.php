<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$role= $_SESSION['role'];
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle job application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_job_id'])) {
    $user_id = $_SESSION['email'];
    $job_id = $_POST['apply_job_id'];

    // Check if the user has already applied for the job
    $check_application_query = "SELECT COUNT(*) AS application_count FROM applied_job WHERE id = '$job_id' AND applied_by = '$user_id'";
    $check_application_result = $conn->query($check_application_query);
    $application_count = ($check_application_result->num_rows > 0) ? $check_application_result->fetch_assoc()['application_count'] : 0;
    // Check if CV exists for the user
$cv_query = "SELECT COUNT(*) AS cv_count FROM cv WHERE email = '$user_id'AND del='false'";
$cv_result = $conn->query($cv_query);
$cv_count = ($cv_result->num_rows > 0) ? $cv_result->fetch_assoc()['cv_count'] : 0;

if ($cv_count == 0) {
    echo "<script>alert('Please update your CV first.');</script>";
    exit(); // Stop further execution if CV does not exist
}
else {
    if ($application_count == 0) {
        $apply_query = "INSERT INTO applied_job (id, job_title, employer_email, applied_by, status) 
                        SELECT id, job_title, email, '$user_id', 'applied' FROM jobs WHERE id = '$job_id'";
        if ($conn->query($apply_query)) {
            echo "<script>alert('Successfully applied for the job.'); window.location.href = 'dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to apply for the job.');</script>";
        }
    } else {
        echo "<script>alert('You have already applied for this job.'); window.location.href = 'dashboard.php';</script>";
        exit();
    }
}}

// Fetch job details if job_id is set
$job_details = null;
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $job_details_query = "SELECT * FROM jobs WHERE id = '$job_id'";
    $job_details_result = $conn->query($job_details_query);

    if ($job_details_result && $job_details_result->num_rows > 0) {
        $job_details = $job_details_result->fetch_assoc();
        
        // Fetch username of job poster
        $employer_email = $job_details['email'];
        $get_username_query = "SELECT name FROM users WHERE email = '$employer_email'";
        $username_result = $conn->query($get_username_query);

        if ($username_result && $username_result->num_rows > 0) {
            $posted_by = $username_result->fetch_assoc()['name'];
        } else {
            $posted_by = "Unknown"; // Default if username not found
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Job Details</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f7f9;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: 40px auto;
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s;
}

.container:hover {
    transform: scale(1.02);
}

h1 {
    font-size: 28px;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.job-details {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e1e1e1;
}

.job-details h2 {
    font-size: 24px;
    color: #007BFF;
    margin-bottom: 10px;
}

.job-details p {
    margin: 8px 0;
    line-height: 1.6;
}

.apply-btn {
    background-color: #007BFF;
    color: white;
    padding: 12px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    border: none;
    transition: background-color 0.3s ease;
    width: 100%; /* Full width */
}

.apply-btn:hover {
    background-color: #0056b3;
}

.posted-by {
    font-style: italic;
    margin-top: 10px;
    color: #555;
    text-align: right;
}

.back-button {
    background-color: #6c757d;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s ease;
    
}

.back-button:hover {
    background-color: #495057;
}

    </style>
</head>
<body>
    <div class="container">
        <?php if ($job_details): ?>
            <div class="job-details">
                <center><h1>Job Details</h1></center>
                <h2><?php echo $job_details['job_title']; ?></h2>
                <p><strong>Company Name:</strong> <?php echo $job_details['company_name']; ?></p>
                <p><strong>Location:</strong> <?php echo $job_details['location']; ?></p>
                <p><strong>Employment Type:</strong> <?php echo $job_details['employment_type']; ?></p>
                <p><strong>Salary:</strong> <?php echo $job_details['salary']; ?></p>
                <p><strong>Required Experience:</strong> <?php echo ($job_details['required_experience'] == 0 ? "No Experience Required" : $job_details['required_experience'] . " Years"); ?></p>
                <p><strong>Education Level:</strong> <?php echo $job_details['education_level']; ?></p>
                <p><strong>Skills:</strong> <?php echo $job_details['skills']; ?></p>
                <p><strong>Application Deadline:</strong> <?php echo $job_details['application_deadline']; ?></p>
                <p><strong>Benefits:</strong> <?php echo $job_details['benefits']; ?></p>
                <p><strong>Company Overview:</strong> <?php echo $job_details['company_overview']; ?></p>
                <p><strong>Category:</strong> <?php echo $job_details['category_name']; ?></p>

                <?php if ($role != 'employer'&&$role != 'admin'): ?>
                    <form method="POST">
                        <input type="hidden" name="apply_job_id" value="<?php echo $job_details['id']; ?>">
                        <?php
                        // Check if user has already applied
                        $check_application_query = "SELECT COUNT(*) AS application_count FROM applied_job WHERE id = '$job_id' AND applied_by = '" . $_SESSION['email'] . "'";
                        $check_application_result = $conn->query($check_application_query);
                        $application_count = ($check_application_result->num_rows > 0) ? $check_application_result->fetch_assoc()['application_count'] : 0;

                        if ($application_count == 0) {
                            echo '<button type="submit" class="apply-btn">Apply</button>';
                        } else {
                            echo '<button type="button" class="apply-btn" disabled>Applied</button>';
                        }
                        ?>
                    </form>
                <?php endif; ?>
            </div>
            <div class="posted-by">
                Posted by: <?php echo $posted_by; ?>
            </div>
        <?php else: ?>
            <p>No job details found.</p>
        <?php endif; ?>
    </div>
    <?php if ($_SESSION['role']=="admin")
    {echo '<center><a href="./admin.php" class="back-button">Back to Dashboard</a></center>'; }
    else {
        echo '<center><a href="./dashboard.php" class="back-button">Back to Dashboard</a></center>';
    }?> <?php $conn->close();?>
</body>
</html>

