<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all jobs
$job_requests_result = $conn->query("SELECT * FROM jobs WHERE status = 'pending'");
if ($job_requests_result) {
    $job_requests = $job_requests_result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error fetching job requests: " . $conn->error;
    $job_requests = array();
}

// Fetch all approved jobs
$approved_jobs_result = $conn->query("SELECT * FROM jobs WHERE status = 'approved' AND del = 'false'");
if ($approved_jobs_result) {
    $approved_jobs = $approved_jobs_result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error fetching approved jobs: " . $conn->error;
    $approved_jobs = array();
}

// Fetch all CVs
$cvs_result = $conn->query("SELECT * FROM cv WHERE del = 'false'");
if ($cvs_result) {
    $cvs = $cvs_result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error fetching CVs: " . $conn->error;
    $cvs = array();
}

// Fetch all users
$users_result = $conn->query("SELECT * FROM users WHERE del = 'false'");
if ($users_result) {
    $users = $users_result->fetch_all(MYSQLI_ASSOC);
} else {
    echo "Error fetching users: " . $conn->error;
    $users = array();
}

// Approve or reject job requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $job_id = $_POST['job_id'];
        $approve_query = "UPDATE jobs SET status = 'approved' WHERE id = $job_id";
        if ($conn->query($approve_query) === TRUE) {
            
            $_SESSION['show_section'] = 'job_requests'; // Set session variable
        } else {
            echo "Error approving job request: " . $conn->error;
        }
    } elseif (isset($_POST['reject'])) {
        $job_id = $_POST['job_id'];
        $reject_query = "UPDATE jobs SET status = 'rejected' WHERE id = $job_id";
        if ($conn->query($reject_query) === TRUE) {
            $_SESSION['show_section'] = 'job_requests'; // Set session variable
        } else {
            echo "Error rejecting job request: " . $conn->error;
        }
    } elseif (isset($_POST['delete_cv'])) {
        $cv_id = $_POST['cv_id'];
        $delete_cv_query = "UPDATE cv SET del = 'true' WHERE id = $cv_id";
        if ($conn->query($delete_cv_query) === TRUE) {
            $_SESSION['show_section'] = 'cvs'; // Set session variable
            header("Location: admin.php");
            exit();
        } else {
            echo "Error deleting CV: " . $conn->error;
        }
    } elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        if ($user_id == $_SESSION['user_id']) {
            echo "Error: Cannot delete own user account.";
        } else {
            $delete_user_query = "UPDATE users SET del = 'true' WHERE id = $user_id";
            if ($conn->query($delete_user_query) === TRUE) {
                $_SESSION['show_section'] = 'user_details'; // Set session variable
                header("Location: admin.php");
            } else {
                echo "Error deleting user: " . $conn->error;
            }
        }
    } elseif (isset($_POST['delete_job'])) {
        $job_id = $_POST['job_id'];
        $sql = "UPDATE jobs SET del = 'true' WHERE id = $job_id";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['show_section'] = 'posted_jobs'; // Set session variable
            header("Location: admin.php");
        } else {
            echo "Error deleting job: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
       
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f8ff;
    margin: 0;
    padding: 0;
}

h2 {
    color: #333333;
    margin-top: 0;
}

/* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #f0f8ff;
    color: black;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px 0;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
}

.sidebar h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

.sidebar a {
    width: 100%;
    padding: 25px 20px;
    color: black;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background-color: gray;
    width: calc(100% - 40px);
}

.sidebar .icon {
    margin-right: 10px;
    font-size: 18px;
}

/* Content Styles */
.content {
    margin-left: 250px;
    padding: 20px;
}

/* Section Styles */
.section {
    display: none;
}

.section table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.section th, .section td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #dddddd;
}

.section th {
    background-color: #f8f9fa;
    color: #333333;
}

.section .action-buttons {
    display: flex;
    gap: 10px;
}

.section .action-buttons form {
    display: inline;
}

.section .action-buttons button {
    padding: 5px 10px;
    font-size: 14px;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.section .action-buttons button[name="approve"] {
    background-color: #28a745;
    color: white;
}

.section .action-buttons button[name="approve"]:hover {
    background-color: #218838;
}

.section .action-buttons button[name="reject"] {
    background-color: #dc3545;
    color: white;
}

.section a {
    text-decoration: none;
    color: black;
    background-color: 	#32CD32;
    border: 1px solid black;
    padding: 2px;
    border-radius: 5px;
}
.section a:hover {
    color: white;
}
.section .action-buttons button[name="reject"]:hover {
    background-color: #c82333;
}

.section .action-buttons button[name="delete_job"],
.section .action-buttons button[name="delete_cv"],
.section .action-buttons button[name="delete_user"] {
    background-color: #ffc107;
    color: black;
}

.section .action-buttons button[name="delete_job"]:hover,
.section .action-buttons button[name="delete_cv"]:hover,
.section .action-buttons button[name="delete_user"]:hover {
    background-color: #e0a800;
   
}



/* Responsive Design */
@media (max-width: 768px) {
    .content {
        margin-left: 0;
        padding: 10px;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        box-shadow: none;
    }

    .sidebar h2 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .sidebar a {
        padding: 10px;
    }
}

    </style>
</head>
<body>
    
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <a href="#job_requests" onclick="showSection('job_requests')">
            <div class="icon">📄</div> Job Requests
        </a>
        <a href="#posted_jobs" onclick="showSection('posted_jobs')">
            <div class="icon">✅</div> Posted Jobs
        </a>
        <a href="#cvs" onclick="showSection('cvs')">
            <div class="icon">📂</div> CVs
        </a>
        <a href="#user_details" onclick="showSection('user_details')">
            <div class="icon">👥</div> User Details
        </a>
        <a href="logout.php">
            <div class="icon">🔓</div> Logout
        </a>
    </div>
    <div class="content">
        <!-- Job Requests Section -->
        <div id="job_requests" class="section">
            <h2>Job Requests</h2>
            <?php if (!empty($job_requests)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company Name</th>
                           
                            <th>Requirements</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($job_requests as $row): ?>
                            <tr>
                                <td><?php echo $row['job_title']; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo isset($row['required_experience']) ? $row['required_experience'].' Years' : 'N/A'; ?></td>
                                <td><a href="./job_details.php?job_id=<?php echo $row['id']; ?>">View</a></td>
                                <td class="action-buttons">
                                    <form action="admin.php" method="post">
                                        <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="approve">Approve</button>
                                        <button type="submit" name="reject">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pending job approvals.</p>
            <?php endif; ?>
        </div>

        <!-- Posted Jobs Section -->
        <div id="posted_jobs" class="section">
            <h2>Posted Jobs</h2>
            <?php if (!empty($approved_jobs)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Company Name</th>
                            <th>Location</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($approved_jobs as $row): ?>
                            <tr>
                                <td><?php echo $row['job_title']; ?></td>
                                <td><?php echo $row['company_name']; ?></td>
                                <td><?php echo $row['location']; ?></td>
                                <td><a href="./job_details.php?job_id=<?php echo $row['id']; ?>">View</a></td>
                                <td class="action-buttons">
                                
                                    <form action="admin.php" method="post" onsubmit="return confirm('Are you sure you want to delete this job?');">
                                        <input type="hidden" name="job_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_job">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No approved jobs.</p>
            <?php endif; ?>
        </div>

        
        <!-- CVs Section -->
<div id="cvs" class="section">
    <h2>CVs</h2>
    <?php if (!empty($cvs)): ?>
        <table>
            <thead>
                <tr>
                    
                    <th>Name</th>
                    <th>View CV</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cvs as $row): ?>
                    <tr>
                        
                        <td><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
                         
                        <td><a href="generate_cv_pdf.php?cv_id=<?php echo $row['id']; ?>">View CV</a></td>
                        <td class="action-buttons">
                            
                            <form action="admin.php" method="post" onsubmit="return confirm('Are you sure you want to delete this CV?');">
                                <input type="hidden" name="cv_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_cv">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No CVs found.</p>
    <?php endif; ?>
</div>


        <!-- User Details Section -->
        <div id="user_details" class="section">
            <h2>User Details</h2>
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $row): ?>
                            <tr>
                            <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td class="action-buttons">
                                    <?php if ($row['role'] != 'admin'): ?>
                                        <form action="edit_user.php" method="get">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit">Edit</button>
                                        </form>
                                        <form action="admin.php" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_user">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if session variable is set
        const sectionToShow = '<?php echo isset($_SESSION['show_section']) ? $_SESSION['show_section'] : ''; ?>';
        
        if (sectionToShow) {
            showSection(sectionToShow);
        }
    });

    function showSection(sectionId) {
        const sections = document.querySelectorAll('.section');
        sections.forEach(section => {
            if (section.id === sectionId) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });
    }
</script>

</body>
</html>
