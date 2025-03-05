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
$role = $_SESSION['role'];

// Fetch categories for the dropdown list
$category_query = "SELECT DISTINCT category_name FROM jobs WHERE status = 'approved' AND del = 'false'";
$category_result = $conn->query($category_query);

// Handle category selection
$category = isset($_GET['category']) ? $_GET['category'] : 'Technology'; //Default category
$category_jobs_query = "SELECT * FROM jobs WHERE category_name = '$category' AND status = 'approved' AND del = 'false'";
$category_jobs_result = $conn->query($category_jobs_query);

// Fetch notifications count for the user
$user_id = $_SESSION['email'];

// Handle job application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_job_id'])) {
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
} else {
    if ($application_count == 0) {
        // Fetch job details 
        $job_query = "SELECT id, job_title, email FROM jobs WHERE id = '$job_id'";
        $job_result = $conn->query($job_query);
        
        if ($job_result->num_rows > 0) {
            $job = $job_result->fetch_assoc();
            $job_title = $job['job_title'];
            $employer_email = $job['email'];
            $applicant_name = $_SESSION['name'];
    
            // Apply for the job
            $apply_query = "INSERT INTO applied_job (id, job_title, employer_email, applied_by, status) 
                            VALUES ('$job_id', '$job_title', '$employer_email', '$user_id', 'applied')";
            if ($conn->query($apply_query)) {
                // Insert notification for employer
                $notification_message = $conn->real_escape_string("$applicant_name has applied for your job titled '$job_title'");
                $insert_notification_query = "INSERT INTO notifications (user_id, message, status, created_at) 
                                              VALUES ('$employer_email', '$notification_message', 'unread', NOW())";
                $conn->query($insert_notification_query);
    
                echo "<script>alert('Successfully applied for the job.');</script>";
            } else {
                echo "<script>alert('Failed to apply for the job.');</script>";
            }
        } else {
            echo "<script>alert('Job not found.');</script>";
        }
    } else {
        echo "<script>alert('You have already applied for this job.');</script>";
    }}
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Jobs</title>
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

        main {
            padding: 20px;
        }

        h2, h3 {
            color: #333333;
        }

        .job-listing-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .job-listing {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 10px;
            padding: 20px;
            flex: 0 1 calc(25%-20px);
            max-width: calc(25%); 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            margin-right: 20px;
            margin-left: 20px;
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

        select {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .apply-link {
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 4px 5px;
            
        }

        .apply-link:hover {
            background-color: green;
            color: white; 
        }
        #ram {
            display: inline;
        }

        #hari:hover {
            color: white;
            background-color: blue;
            
            
        }
        #hari{
            background-color: white;
            color: black;
            border: 1px solid black;
            border-radius: 5px;
            padding: 4px 5px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<header>
    <div class="navbar">
        <h3>KaamGhar</h3>
        <div class="nav-links">
            <a class="nav-button" href="./dashboard.php">Dashboard</a>
        </div>
    </div>
</header>
<main>
    <h2>Welcome, <?php echo isset($_SESSION['email']) ? $_SESSION['name'] : ''; ?></h2>
    <h3>Select Category</h3>
    <form method="GET" action="category.php">
        <select name="category" onchange="this.form.submit()">
            <option value="">Select a category</option>
            <?php while ($category_row = $category_result->fetch_assoc()): ?>
                <option value="<?php echo $category_row['category_name']; ?>" <?php echo ($category_row['category_name'] == $category) ? 'selected' : ''; ?>>
                    <?php echo $category_row['category_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
    <h3>Available Jobs in <?php echo htmlspecialchars($category); ?></h3>
    <?php if ($category_jobs_result && $category_jobs_result->num_rows > 0): ?>
        <div class="job-listing-container">
            <?php while ($job = $category_jobs_result->fetch_assoc()): 
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
                    <a id="hari" href="./job_details.php?job_id=<?php echo $job['id']; ?>">View Details</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if ($role != 'employer' && $application_count == 0): ?>
                        <form id= "ram" method="POST" action="category.php?category=<?php echo urlencode($category); ?>">
                            <input type="hidden" name="apply_job_id" value="<?php echo $job['id']; ?>">
                            <button type="submit" class="apply-link">Apply Now</button>
                        </form>
                    <?php elseif ($role != 'employer'): ?>
                        <span class="applied-status">Applied</span>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No jobs found in this category.</p>
    <?php endif; ?>
</main>
</body>
</html>
<?php $conn->close(); ?>
