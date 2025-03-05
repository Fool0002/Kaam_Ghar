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

// Process form data and insert into database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
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
    $status = 'pending'; 
    $email = $_SESSION['email'];

    // Check if a job with the same details already exists
    $checkQuery = "SELECT del FROM jobs 
                   WHERE job_title = '$job_title' 
                   AND job_description = '$job_description'
                   AND company_name = '$company_name'
                   AND location = '$location'
                   AND employment_type = '$employment_type'
                   AND salary = '$salary'
                   AND required_experience = '$required_experience'
                   AND education_level = '$education_level'
                   AND skills = '$skills'
                   AND application_deadline = '$application_deadline'
                   AND benefits = '$benefits'
                   AND company_overview = '$company_overview'
                   AND contact_information = '$contact_information'
                   AND category_name = '$job_category' 
                   LIMIT 1";

    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        $existingJob = $result->fetch_assoc();
        if ($existingJob['del'] == 'false') {
            echo "<script>alert('Job with the same specifications is already in the system.');</script>";
        } else {
            // Insert the new job
            $stmt = "INSERT INTO jobs (user_id, job_title, job_description, company_name, location, employment_type, salary, required_experience, education_level, skills, application_deadline, benefits, company_overview, contact_information, category_name, status, email, del) 
                    VALUES ('$user_id', '$job_title', '$job_description', '$company_name', '$location', '$employment_type', '$salary', '$required_experience', '$education_level', '$skills', '$application_deadline', '$benefits', '$company_overview', '$contact_information', '$job_category', '$status', '$email', 'false')";

            if ($conn->query($stmt)) {
                echo "<script>alert('Job has been successfully added and is pending approval.');</script>";
            } else {
                echo "Error: " . $stmt . "<br>" . $conn->error;
            }
        }
    } else {
        // Insert the new job
        $stmt = "INSERT INTO jobs (user_id, job_title, job_description, company_name, location, employment_type, salary, required_experience, education_level, skills, application_deadline, benefits, company_overview, contact_information, category_name, status, email, del) 
                VALUES ('$user_id', '$job_title', '$job_description', '$company_name', '$location', '$employment_type', '$salary', '$required_experience', '$education_level', '$skills', '$application_deadline', '$benefits', '$company_overview', '$contact_information', '$job_category', '$status', '$email', 'false')";

        if ($conn->query($stmt)) {
            echo "<script>alert('Job has been successfully added and is pending approval.');</script>";
        } else {
            echo "Error: " . $stmt . "<br>" . $conn->error;
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
    <title>Add Job Description</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
}

.container {
    width: 50%;
    margin: auto;
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
textarea,
select {
    width: calc(100% - 22px); /* Adjusting for padding and border */
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
}

input[type="date"] {
    width: calc(100% - 22px); 
}

textarea {
    resize: vertical; 
}

input[type="submit"],
.back-button {
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

input[type="submit"]:hover,
.back-button:hover {
    background-color: #45a049;
}

.back-button {
    background-color: #6c757d;
    text-decoration: none;
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
        <h2>Add Job Description</h2>
        <form action="" method="post" onsubmit="return validateForm()">
            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" pattern="[a-zA-Z\s-]+" title="Only alphabets, spaces, and dashes are allowed" required>

            <label for="job_description">Job Description:</label>
            <textarea id="job_description" name="job_description" rows="5" required></textarea>

            <label for="company_name">Company Name:</label>
            <input type="text" id="company_name" name="company_name" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" pattern="[a-zA-Z0-9\s-]+" title="Only alphabets, numbers, spaces, and dashes are allowed" required>

            <label for="employment_type">Employment Type:</label>
            <select id="employment_type" name="employment_type" required>
                <option value="">Select Employment Type</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
            </select>

            <label for="salary">Salary:</label>
            <input type="text" id="salary" name="salary">

            <label for="required_experience">Required Experience (years):</label>
            <input type="text" id="required_experience" name="required_experience" pattern="\d+" title="Only numbers are allowed" required>

            <label for="education_level">Education Level:</label>
            <select id="education_level" name="education_level" required>
                <option value="">Select Education Level</option>
                <option value="SEE">SEE</option>
                <option value="+2">+2</option>
                <option value="Bachelors">Bachelors</option>  
                <option value="PhD">PhD</option>
                <option value="No Education Required">No Education</option>
            </select>

            <label for="skills">Skills:</label>
            <textarea id="skills" name="skills" rows="3" pattern="[a-zA-Z0-9\s,-#]+" title="Only alphabets, numbers, spaces, commas, dashes, and hashtags are allowed"></textarea>

            <label for="application_deadline">Application Deadline:</label>
            <input type="date" id="application_deadline" name="application_deadline">

            <label for="benefits">Benefits:</label>
            <textarea id="benefits" name="benefits" rows="3" pattern="[a-zA-Z0-9\s,-]+" title="Only alphabets, numbers, spaces, and commas are allowed"></textarea>

            <label for="company_overview">Company Overview:</label>
            <textarea id="company_overview" name="company_overview" rows="3" pattern="[a-zA-Z0-9\s,-]+" title="Only alphabets, numbers, spaces, and commas are allowed"></textarea>

            <label for="contact_information">Contact Information:</label>
            <input type="text" id="contact_information" name="contact_information" pattern="[0-9]{10}" title="Enter a valid 10-digit phone number" required>

            <label for="job_category">Job Category:</label>
            <select id="job_category" name="job_category" required>
                <option value="">Select a category</option>
                <option value="Writing">Writing</option>
                <option value="Electronic">Electronic</option>
                <option value="Official">Official</option>
                <option value="Technology">Technology</option>
                <option value="Marketing">Marketing</option>
                <option value="Design">Design</option>
                <option value="Human Resources">Human Resources</option>
                <option value="Project Management">Project Management</option>
                <option value="Data Analysis">Data Analysis</option>
                <option value="Others">Others</option>
            </select>

            <input type="submit" value="Submit">
        </form></div>
        <br><center><a href="./dashboard.php" class="back-button">Back to Dashboard</a></center>
    </div>
    <script>
        function validateForm() {
            const salary = document.getElementById('salary').value;
            const requiredExperience = document.getElementById('required_experience').value;
            const contactInformation = document.getElementById('contact_information').value;

            if (isNaN(salary) || salary.trim() === "") {
                alert("Salary must be a number.");
                return false;
            }

            if (isNaN(requiredExperience) || requiredExperience.trim() === "") {
                alert("Required Experience must be a number.");
                return false;
            }

            if (!/^\d{10}$/.test(contactInformation)) {
                alert("Contact Information must be exactly 10 digits long.");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
