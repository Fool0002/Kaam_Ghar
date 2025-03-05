<?php
session_start();

// Database connection parameters
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

// Retrieve session email
$email = $_SESSION['email'];

// Check if CV already exists for the session email
$sql_check = "SELECT * FROM cv WHERE email = '$email' AND del='false'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // CV exists, fetch the data
    $row = $result->fetch_assoc();
    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $age = $row['age'];
    $gender = $row['gender'];
    $experience = $row['experience'];
    $job_title = $row['job_title'];
    $degree = $row['degree'];
    $university = $row['university'];
    $grad_year = $row['grad_year'];
    $skills = $row['skills'];
    
    $profile_picture = $row['profile_picture'];
} else {
    // No CV found, initialize variables
    $fname = "";
    $lname = "";
    $age = "";
    $gender = "";
    $experience = "";
    $job_title = "";
    $degree = "";
    $university = "";
    $grad_year = "";
    $skills = "";
    $profile_picture = "";
}

// Handle form submission
if (isset($_POST['submit'])) {
    // Retrieve form data
    $fname = $_POST['f_name'];
    $lname = $_POST['l_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $experience = $_POST['experience'];
    $job_title = $_POST['job_title'];
    $degree = $_POST['degree'];
    $university = $_POST['university'];
    $grad_year = $_POST['grad_year'];
    $skills = $_POST['skills'];

    // File upload handling for profile picture
    if ($_FILES['profile_picture']['error'] === 0) {
        $targetDir = "profile_pictures/";
        $profile_picture = $targetDir . basename($_FILES["profile_picture"]["name"]);

        // Check if the directory exists, if not, create it
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory with full permissions (0777)
        }

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) {
            // File uploaded successfully
        } else {
            echo "Error uploading file.";
            $profile_picture = ""; // Reset to empty if upload failed
        }
    } else {
        // If no new file uploaded, retain the existing profile picture path
        if ($result->num_rows > 0) {
            $profile_picture = $row['profile_picture'];
        }
    }


    // SQL query to update or insert CV data into the database
    if ($result->num_rows > 0) {
        // CV exists, update the record
        $sql = "UPDATE cv SET 
                first_name = '$fname', 
                last_name = '$lname', 
                age = $age, 
                gender = '$gender', 
                profile_picture = '$profile_picture', 
                experience = $experience, 
                job_title = '$job_title', 
                degree = '$degree', 
                university = '$university', 
                grad_year = '$grad_year', 
                skills = '$skills' 
                WHERE email = '$email'";

                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('CV updated successfully.');</script>";
                } else {
                    echo "Error updating CV: " . $conn->error;
                }
    } else {
       // Get the count of existing CV records
$count_query = "SELECT COUNT(*) as total FROM cv";
$count_result = $conn->query($count_query);
if ($count_result->num_rows > 0) {
    $row = $count_result->fetch_assoc();
    $total_cvs = $row['total'];
    // Manually assign the next available ID
    $next_id = $total_cvs + 1;
    
    // Now insert the CV with the manually assigned ID
    $sql = "INSERT INTO cv (id, first_name, last_name, age, gender, profile_picture, experience, job_title, degree, university, grad_year, skills, email)
            VALUES ('$next_id', '$fname', '$lname', $age, '$gender', '$profile_picture', $experience, '$job_title', '$degree', '$university', $grad_year, '$skills', '$email')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('CV submitted successfully.');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}}
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your CV</title>
    <style>
 
body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
}

header {
    text-align: center;
    margin-bottom: 20px;
}

header h1 {
    margin: 20px 0;
}

main {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 800px;
    overflow-y: auto;
}

h2 {
    margin: 20px 0 10px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    margin-bottom: 20px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    box-sizing: border-box;
}

button[type="submit"] {
    background-color: #28a745;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button[type="submit"]:hover {
    background-color: #218838;
}

.error {
    margin-bottom: 20px;
}

.flex-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}

.flex-container > div {
    flex: 1;
    min-width: 48%;
    margin-bottom: 20px;
}

.full-width {
    width: 100%;
}

textarea {
    height: 100px;
    resize: none;
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
}

.back-button:hover {
    background-color: #495057;
}
    </style>
</head>
<body>

    <main>
        <div class="error" id="error" style="color: red;"></div>
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <h2>Personal Information</h2>
            <div class="flex-container">
                <div>
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="f_name" value="<?php echo htmlspecialchars($fname); ?>" required>
                </div>
                <div>
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="l_name"value="<?php echo htmlspecialchars($lname); ?>" required>
                </div>
            </div>
            <div class="flex-container">
                <div>
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($age); ?>"required>
                </div>
                <div>
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male" <?php if ($gender == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($gender == 'female') echo 'selected'; ?>>Female</option>
                    <option value="other" <?php if ($gender == 'other') echo 'selected'; ?>>Other</option>
                </select>
                </div>
            </div>
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
        <?php if (!empty($profile_picture)): ?>
            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">
        <?php endif; ?>

            <h2>Work Experience</h2>
            <div class="flex-container">
                <div class="full-width">
                    <label for="job_title">Field of Work:</label>
                    <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($job_title); ?>">
                </div>
                <div class="full-width">
                    <label for="experience">Years of Experience:</label>
                    <input type="number" id="experience" name="experience"value="<?php echo htmlspecialchars($experience); ?>">
                </div>
            </div>

            <h2>Education</h2>
            <div class="flex-container">
                <div>
                    <label for="degree">Degree:</label>
                    <select id="degree" name="degree" required>
                    <option value="S.E.E" <?php if ($degree == 'S.E.E') echo 'selected'; ?>>S.E.E</option>
                    <option value="+2" <?php if ($degree == '+2') echo 'selected'; ?>>+2</option>
                    <option value="Bachelors" <?php if ($degree == 'Bachelors') echo 'selected'; ?>>Bachelors</option>
                    <option value="P.hd" <?php if ($degree == 'P.hd') echo 'selected'; ?>>P.hd</option>
                </select>
                    
                </div>
                <div>
                    <label for="university">University/Institute:</label>
                    <input type="text" id="university" name="university"value="<?php echo htmlspecialchars($university); ?>">
                </div>
                <div>
                    <label for="grad_year">Graduation Year:</label>
                    <input type="text" id="grad_year" name="grad_year" value="<?php echo htmlspecialchars($grad_year); ?>">
                </div>
            </div>

            <h2>Skills</h2>
            <label for="skills">Skills (separated by commas):</label>
            <textarea id="skills" name="skills" ><?php echo htmlspecialchars($skills); ?></textarea>

            <button type="submit" name="submit">Submit CV</button>
        </form><br>
        <center><a href="./dashboard.php" class="back-button">Back to Dashboard</a></center>
    </main>
    <script>
       function validateForm() {
    var firstName = document.getElementById("firstname").value.trim();
    var lastName = document.getElementById("lastname").value.trim();
    var age = document.getElementById("age").value;
    var jobTitle = document.getElementById("job_title").value.trim();
    var experience = document.getElementById("experience").value;
    var university = document.getElementById("university").value.trim();
    var gradYear = document.getElementById("grad_year").value.trim();
    var skills = document.getElementById("skills").value.trim();
    var error = "";

    // Validation patterns
    var namePattern = /^[A-Za-z]+$/;
    var nameWithSpacesPattern = /^[A-Za-z\s]+$/;
    var universityPattern = /^[A-Za-z.\s]+$/;
    var work = /^[A-Za-z.- ]+$/;

    // Validate first name and last name
    if (!namePattern.test(firstName)) {
        error = "First name cannot contain numbers or spaces.";
    } else if (!namePattern.test(lastName)) {
        error = "Last name cannot contain numbers or spaces.";
    }

    // Validate age
    if (age < 14 || age > 80) {
        error = "Age must be between 14 and 80.";
    }

    // Validate field of work (job title)
    if (!work.test(jobTitle)) {
        error = "Field of work can only contain alphabets and spaces.";
    } 

    // Validate years of experience
    if (experience > 50) {
        error = "Years of experience cannot be more than 50.";
    } else if (experience < 0){
        error = "Years of experience cannot be less than 0.";
    } else if (experience > (age-14) ){
        error = "Years of experience is invalid because of age.";
    }

    // Validate university
    if (!universityPattern.test(university)) {
        error = "University can only contain alphabets, dots, and spaces.";
    }

    // Validate graduation year
    var currentYear = new Date().getFullYear();
    if (gradYear > currentYear) {
        error = "Graduation year cannot be in the future.";
    } else if (gradYear < currentYear - age) {
        error = "Graduation year cannot be in the past.";
    }



    if (error) {
        document.getElementById("error").innerText = error;
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}

    </script>
    
</body>
</html>


