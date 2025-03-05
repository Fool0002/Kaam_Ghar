<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if job_id is set in URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch job details from database
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Assign fetched values to variables
        $job_title = $row['job_title'];
        $job_description = $row['job_description'];
        $company_name = $row['company_name'];
        $location = $row['location'];
        $employment_type = $row['employment_type'];
        $salary = $row['salary'];
        $required_experience = $row['required_experience'];
        $education_level = $row['education_level'];
        $skills = $row['skills'];
        $job_category = $row['category_name'];
        $application_deadline = $row['application_deadline'];
        $benefits = $row['benefits'];
        $company_overview = $row['company_overview'];
        $contact_information = $row['contact_information'];
    } else {
        echo "Job not found.";
        exit;
    }
} else {
    echo "Job ID not specified.";
    exit;
}

// Close prepared statement
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $company_name = $_POST['company_name'];
    $location = $_POST['location'];
    $employment_type = $_POST['employment_type'];
    $salary = $_POST['salary'];
    $required_experience = $_POST['required_experience'];
    $education_level = $_POST['education_level'];
    $skills = $_POST['skills'];
    $job_category = $_POST['job_category'];
    $application_deadline = $_POST['application_deadline'];
    $benefits = $_POST['benefits'];
    $company_overview = $_POST['company_overview'];
    $contact_information = $_POST['contact_information'];

    // Update job details in database
    $sql = "UPDATE jobs SET 
            job_title='$job_title', 
            job_description='$job_description', 
            company_name='$company_name', 
            location='$location', 
            employment_type='$employment_type', 
            salary='$salary', 
            required_experience='$required_experience', 
            education_level='$education_level', 
            skills='$skills', 
            category_name='$job_category', 
            application_deadline='$application_deadline', 
            benefits='$benefits', 
            company_overview='$company_overview', 
            contact_information='$contact_information' 
            WHERE id=$job_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Job details updated successfully.');</script>";
    } else {
        echo "<script>alert('Error updating jobs.');</script>";
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job Description</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
    }

    .container {
        width: 60%;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    form {
        width: 100%;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #666;
    }

    input[type="text"],
    input[type="date"],
    textarea,
    select {
        width: calc(100% - 22px);
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px;
    }

    textarea {
        resize: vertical;
    }

    input[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .back-button {
        display: inline-block;
        background-color: #6c757d;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        
    }

    .back-button:hover {
        background-color: #495057;
    }

    .center-text {
        text-align: center;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .container {
            width: 80%;
        }
    }
    </style>
    
</head>
<body>
    <div class="container">
        <h2>Edit Job Description</h2>
        <form action="" method="post" onsubmit="return validateForm()">
            <!-- Hidden input field to store job_id -->
            <input type="hidden" id="job_id" name="job_id" value="<?php echo $job_id; ?>">

            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" value="<?php echo $job_title; ?>" required>

            <label for="job_description">Job Description:</label>
            <textarea id="job_description" name="job_description" rows="5" required><?php echo $job_description; ?></textarea>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" value="<?php echo $company_name; ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo $location; ?>" required>

            <label for="employment_type">Employment Type:</label>
            <select id="employment_type" name="employment_type" required>
                <option value="">Select Employment Type</option>
                <option value="Full-time" <?php if ($employment_type == "Full-time") echo "selected"; ?>>Full-time</option>
                <option value="Part-time" <?php if ($employment_type == "Part-time") echo "selected"; ?>>Part-time</option>
            </select>

            <label for="salary">Salary(Rs.):</label>
            <input type="text" id="salary" name="salary" value="<?php echo $salary; ?>">

            <label for="required_experience">Required Experience (years):</label>
            <input type="text" id="required_experience" name="required_experience" value="<?php echo $required_experience; ?>" required>

            <label for="education_level">Education Level:</label>
            <select id="education_level" name="education_level" required>
                <option value="">Select Education Level</option>
                <option value="SEE" <?php if ($education_level == "SEE") echo "selected"; ?>>SEE</option>
                <option value="+2" <?php if ($education_level == "+2") echo "selected"; ?>>+2</option>
                <option value="Bachelors" <?php if ($education_level == "Bachelors") echo "selected"; ?>>Bachelors</option>
                <option value="PhD" <?php if ($education_level == "PhD") echo "selected"; ?>>PhD</option>
            </select>

            <label for="skills">Skills:</label>
            <textarea id="skills" name="skills" rows="3"><?php echo $skills; ?></textarea>

            <label for="application_deadline">Application Deadline:</label>
            <input type="date" id="application_deadline" name="application_deadline" value="<?php echo $application_deadline; ?>">

            <label for="benefits">Benefits:</label>
            <textarea id="benefits" name="benefits" rows="3"><?php echo $benefits; ?></textarea>

            <label for="company_overview">Company Overview:</label>
            <textarea id="company_overview" name="company_overview" rows="3"><?php echo $company_overview; ?></textarea>

            <label for="contact_information">Contact Information:</label>
            <input type="text" id="contact_information" name="contact_information" value="<?php echo $contact_information; ?>" required>

            <label for="job_category">Job Category:</label>
            <select id="job_category" name="job_category" required>
                <option value="">Select a category</option>
                <option value="Writing" <?php if ($job_category == "Writing") echo "selected"; ?>>Writing</option>
                <option value="Electronic" <?php if ($job_category == "Electronic") echo "selected"; ?>>Electronic</option>
                <option value="Official" <?php if ($job_category == "Official") echo "selected"; ?>>Official</option>
                <option value="Others" <?php if ($job_category == "Others") echo "selected"; ?>>Others</option>
            </select>

            <input type="submit" value="Update">
        </form>
        <br><center><a href="./your_job.php" class="back-button">Back to Your Job</a>&nbsp;&nbsp;&nbsp;
        <a href="./dashboard.php" class="back-button">Back to Dashboard</a></center>
    </div>
    <script>
    function validateForm() {
        var salary = document.getElementById('salary').value.trim();
        var required_experience = document.getElementById('required_experience').value.trim();
        var contact_information = document.getElementById('contact_information').value.trim();

        // Validate Salary (numeric check)
        if (salary !== '' && !/^\d+(\.\d+)?$/.test(salary)) {
            alert('Salary must be a valid number');
            return false;
        }

        // Validate Required Experience (numeric check)
        if (required_experience !== '' && !/^\d+$/.test(required_experience)) {
            alert('Required Experience must be a valid number');
            return false;
        }

        // Validate Contact Information (numeric and length check)
        if (contact_information !== '') {
            if (!/^\d+$/.test(contact_information)) {
                alert('Contact Information must be numeric');
                return false;
            }
            if (contact_information.length > 10) {
                alert('Contact Information cannot be more than 10 digits');
                return false;
            } else if 
            (contact_information.length < 10) {
                alert('Contact Information cannot be less than 10 digits');
                return false;
            }
        }

        return true; // Form submission
    }
    </script>
</body>
</html>
