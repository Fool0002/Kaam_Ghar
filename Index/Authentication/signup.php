<?php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and trim form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $role = trim($_POST["role"]);

    // Validate form inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($role)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $sql_check_email = "SELECT id FROM users WHERE email = '$email'";
        $result_check_email = $conn->query($sql_check_email);

        if ($result_check_email->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // Get the count of existing users to determine the next ID
            $sql_count_users = "SELECT COUNT(*) AS total FROM users";
            $result_count_users = $conn->query($sql_count_users);

            if ($result_count_users->num_rows > 0) {
                $row = $result_count_users->fetch_assoc();
                $total_users = $row['total'];
                // Manually assign the next available ID
                $next_id = $total_users + 1;

                // Insert new user into the database
                $sql_insert_user = "INSERT INTO users (id, name, email, password, role) 
                                    VALUES ('$next_id', '$username', '$email', '$password', '$role')";

                if ($conn->query($sql_insert_user) === TRUE) {
                    echo "<script>alert('Signup successful.');</script>";
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "Something went wrong. Please try again.";
                }
            } else {
                $error = "Error retrieving user count.";
            }
        }
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="./signup.css">
    <script>
        function validateForm() {
            var username = document.getElementById("username").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var role = document.getElementById("role").value;
            var error = "";

            // Validate username (no spaces, cannot start with a number)
            if (/\s/.test(username)) {
                error = "Username cannot contain spaces.";
            } else if (/^\d/.test(username)) {
                error = "Username cannot start with a number.";
            }

            // Validate email (must start with an alphabet and follow standard email format)
            var emailPattern = /^[a-zA-Z][a-zA-Z0-9._-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                error = "Invalid email address.";
            }

            // Validate password length
            if (password.length < 8) {
                error = "Password must be at least 8 characters long.";
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                error = "Passwords do not match.";
            }

            // Check if all fields are filled
            if (username === "" || email === "" || password === "" || confirmPassword === "" || role === "") {
                error = "All fields are required.";
            }

            if (error) {
                document.getElementById("error").innerText = error;
                return false;
            }

            return true;
        }

        function checkPasswordStrength() {
            var password = document.getElementById("password").value;
            var strengthBadge = document.getElementById("strengthBadge");
            
            // Strong password: combination of alphabets, numbers, and special characters
var strongPassword = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[!@#\\$%\\^&\\*])(?=.{8,})");

// Medium password: combination of alphabets and numbers
var mediumPassword = new RegExp("^(?=.*[a-zA-Z])(?=.*[0-9])(?=.{8,})");


            if (strongPassword.test(password)) {
                strengthBadge.style.backgroundColor = "green";
                strengthBadge.innerText = "Strong";
            } else if (mediumPassword.test(password)) {
                strengthBadge.style.backgroundColor = "orange";
                strengthBadge.innerText = "Medium";
            } else {
                strengthBadge.style.backgroundColor = "red";
                strengthBadge.innerText = "Weak";
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <div class="error" id="error"><?php if (isset($error)) echo $error; ?></div>
        <form action="" method="post" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" oninput="checkPasswordStrength()" required>
            </div><div class="strength-badge" id="strengthBadge"></div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="employer">Employer</option>
                    <option value="jobseeker">Job Seeker</option>
                </select>
            </div>
            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>
