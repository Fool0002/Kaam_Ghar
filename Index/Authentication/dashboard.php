<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if role is set in session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch notifications count for the user
$user_id = $_SESSION['email'];
$notification_query = "SELECT COUNT(*) AS notification_count FROM notifications WHERE user_id = '$user_id' AND status = 'unread'";
$notification_result = $conn->query($notification_query);
$notification_count = ($notification_result->num_rows > 0) ? $notification_result->fetch_assoc()['notification_count'] : 0;

// Fetch available jobs for employees and employers
$available_jobs_query = "SELECT * FROM jobs WHERE status = 'approved' AND del = 'false'";
$available_jobs_result = $conn->query($available_jobs_query);

// Delete jobs past their deadline
$current_date = date('Y-m-d');
$delete_expired_jobs_query = "DELETE FROM jobs WHERE application_deadline < '$current_date'";
$conn->query($delete_expired_jobs_query);

// Handle job application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_job_id'])) {
    $job_id = $_POST['apply_job_id'];

    // Check if the user has applied for the job or if the application is deleted
$check_application_query = "SELECT del FROM applied_job WHERE id = '$job_id' AND applied_by = '$user_id'";
$check_application_result = $conn->query($check_application_query);

if ($check_application_result->num_rows > 0) {
    $application_info = $check_application_result->fetch_assoc();
    if ($application_info['del'] === 'true') {
        // Application is deleted, allow reapply
        $can_apply = true;
    } else {
        // User has already applied and application is not deleted
        $can_apply = false;
    }
} else {
    // User has not applied
    $can_apply = true;
}

// Fetch job details including employer email
$job_query = "SELECT id, job_title, email FROM jobs WHERE id = '$job_id'";
$job_result = $conn->query($job_query);

if ($job_result->num_rows > 0) {
    $job = $job_result->fetch_assoc();
    $job_title = $job['job_title'];
    $employer_email = $job['email'];
    $applicant_name = $_SESSION['name'];

    // Apply for the job if allowed
    if ($can_apply) {
        // Check if CV exists for the user
$cv_query = "SELECT COUNT(*) AS cv_count FROM cv WHERE email = '$user_id' AND del = 'false'";
$cv_result = $conn->query($cv_query);
$cv_count = ($cv_result->num_rows > 0) ? $cv_result->fetch_assoc()['cv_count'] : 0;

if ($cv_count == 0) {
    echo "<script>alert('Please update your CV first.');</script>";
     
}
else{
        $apply_query = "INSERT INTO applied_job (id, job_title, employer_email, applied_by, status, del) 
                        VALUES ('$job_id', '$job_title', '$employer_email', '$user_id', 'applied', 'false')";
        if ($conn->query($apply_query)) {
            // Insert notification for employer
            $notification_message = $conn->real_escape_string("$applicant_name has applied for your job titled '$job_title'");
            $insert_notification_query = "INSERT INTO notifications (user_id, message, status, created_at) 
                                          VALUES ('$employer_email', '$notification_message', 'unread', NOW())";
            $conn->query($insert_notification_query);

            echo "<script>alert('Successfully applied for the job.');</script>";
        } else {
            echo "<script>alert('Failed to apply for the job.');</script>";
        }}
    } else {
        echo "<script>alert('You have already applied for this job.');</script>";
    }
} else {
    echo "<script>alert('Job not found.');</script>";
}

}
    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

header {
    background-color: white;
    color: #ffffff;
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

.dropdown {
    position: relative;
    display: inline-block;
}

.dropbtn {
    background-color: #007BFF;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {
    background-color: #007BFF;  
    color: white;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.notification {
    position: relative;
    display: inline-block;
    margin-left: 20px;
}

.notification img {
    width: 24px;
    height: 24px;
}

.notification .badge {
    background-color: red;
    color: white;
    padding: 5px 10px;
    border-radius: 50%;
    position: absolute;
    top: -6px;
    right: -10px;
    height: 15px;
    width : 5px;
}

main {
    padding: 20px;
}

h2, h3 {
    color: #333333;
}

.job-listing-container {
    display: flex;
    flex-wrap: wrap;
   ;
    justify-content: space-between; 
}

.job-listing-row {
    width: 100%; 
    margin: 40px;
    display: flex;
    justify-content: space-between; 
}

.job-listing {
    background-color: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 10px;
    padding: 15px;
    flex-basis: calc(25% - 20px); 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
    
    margin-right:20px;
}

.job-listing:hover {
    box-shadow: 0 0 15px blue;
}

.job-listing h4 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #007BFF;
}

.job-listing p {
    margin: 5px 0;
    color: #666666;
}

.job-listing a {
    text-decoration: none;
    color: #007BFF;
    display: inline-block;
    margin-top: 10px;
}

.job-listing a:hover {
    text-decoration: underline;
}

.job-listing span.applied-status {
    display: inline-block;
    background-color: #32CD32;
    padding: 4px 8px;
    font-weight: bold;
    margin-left: 20px;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .job-listing {
        max-width: 100%;
    }
}
#ram{
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 4px 5px;
            
        }
        #hari{
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 4px 5px;
            text-decoration: none;
        }

        #ram:hover {
            
            background-color: green;
            color: white;  
            
        }
        #hari:hover {
            color: white;
            background-color: blue;
            
        }
    </style>
</head>
<body>
<header>
        <div class="navbar">
           <h3>KaamGhar</h3> 
            <div class="nav-links">
               <?php if($_SESSION['role']=="employer") 
               {echo '<a class="nav-button" href="./job.php">Post Job</a>';
                } else {
                   echo '<a class="nav-button" href="./cv.php">Create/Update CV</a>';
                }?>
                <a class="nav-button" href="./category.php">Categories</a>
                <div class="dropdown">
                    <button class="dropbtn">Profile</button>
                    <div class="dropdown-content">
                    <?php if($_SESSION['role']=="employer") 
               {echo '<a  href="./your_job.php">Your Jobs</a>';
                } else {
                   echo '<a  href="./profile.php">Your Profile</a>';
                }?>
                  <?php if($_SESSION['role']=="jobseeker") 
               {echo '<a  href="./apptrack.php">Your Applications</a>';
                }?>
                        <a href="./changepassword.php">Change Password</a>
                        <a href="./logout.php">Logout</a>
                    </div>
                </div>
                <div class="notification">
    <a href="./notifications.php">
        <img src="./bell.png" alt="Notifications">
        <span class="badge" style="background-color: red;">
            
        </span>
    </a>
</div>
            </div>
        </div>
    </header>
    <main>
        <h2>Welcome, <?php echo isset($_SESSION['email']) ? $_SESSION['name'] : '' ; ?></h2>
        <h3>Available Jobs</h3>
        <?php if ($available_jobs_result && $available_jobs_result->num_rows > 0): ?>
    <div class="job-listing-container">
        <?php 
        $counter = 0;
        while ($job = $available_jobs_result->fetch_assoc()): 
            if ($counter % 4 == 0) { // Start a new row every 4 jobs
                if ($counter > 0) {
                    echo '</div>'; 
                }
                echo '<div class="job-listing-row">'; 
            }
            $counter++;
            $deadline = new DateTime($job['application_deadline']);
            $currentDate = new DateTime();
            $remaining_days = $deadline->diff($currentDate)->format("%r%a days remaining");

            // Check if the user has already applied for this job
            $check_application_query = "SELECT COUNT(*) AS application_count FROM applied_job WHERE id = '" . $job['id'] . "' AND applied_by = '$user_id'";
            $check_application_result = $conn->query($check_application_query);
            $application_count = ($check_application_result->num_rows > 0) ? $check_application_result->fetch_assoc()['application_count'] : 0;
        ?>
            <div class="job-listing">
                <h4><?php echo $job['job_title']; ?></h4>
                <p><strong>Company Name:</strong> <?php echo $job['company_name']; ?></p>
                <p><strong>Required Experience:</strong> <?php echo ($job['required_experience'] == 0 ? "No Experience Required." : $job['required_experience'] . " Years"); ?></p>
                <p><strong>Deadline:</strong> <?php echo $remaining_days; ?></p>
                <a id="hari" href="./job_details.php?job_id=<?php echo $job['id']; ?>">View Details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php if ($role != 'employer' && $application_count == 0): ?>
                    <a id="ram" href="javascript:void(0);" onclick="confirmApplyJob(<?php echo $job['id']; ?>);">Apply</a>
                <?php elseif ($role != 'employer'): ?>
                    <span class="applied-status">Applied</span>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
        </div> 
    </div>
<?php else: ?>
    <p>No available jobs at the moment.</p>
<?php endif; ?>

    </main>
    <form id="applyForm" method="POST" style="display: none;">
        <input type="hidden" name="apply_job_id" id="applyJobId">
    </form>
    <script>
        function confirmApplyJob(jobId) {
            if (confirm('Are you sure you want to apply for this job?')) {
                document.getElementById('applyJobId').value = jobId;
                document.getElementById('applyForm').submit();
            }
        }
    </script>
</body>
</html>
