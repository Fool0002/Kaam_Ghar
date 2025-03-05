<?php
// Database connection 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oddjob";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$name = $_POST['name'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$experience = $_POST['experience'];
$job_title = $_POST['job_title'];
$degree = $_POST['degree'];
$university = $_POST['university'];
$grad_year = $_POST['grad_year'];
$skills = $_POST['skills'];

// File upload handling for profile picture
$profile_picture = "";
if ($_FILES['profile_picture']['error'] === 0) {
    $targetDir = "profile_pictures/";
    $profile_picture = $targetDir . basename($_FILES["profile_picture"]["name"]);
    $targetDir = "profile_pictures/";

// Check if the directory exists, if not, create it
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true); // Create directory with full permissions (0777)
}

// Continue with file upload handling

    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture);
}

// SQL query to insert CV data into the database
$sql = "INSERT INTO cv (name, age, gender, profile_picture, experience, job_title, degree, university, grad_year, skills)
        VALUES ('$name', $age, '$gender', '$profile_picture', $experience, '$job_title', '$degree', '$university', $grad_year, '$skills')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('CV submitted successfully.');</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
