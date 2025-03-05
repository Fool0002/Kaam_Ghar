<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "oddjob";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_SESSION['email']; // Retrieve email from session

    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch current password from database
    $sql = "SELECT password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_password = $row['password'];

        // Verify old password matches
        if ($old_password !== $current_password) {
            $error = "Old password is incorrect.";
        } else {
            // Validate new password
            if (strlen($new_password) < 8) {
                $error = "New password must be at least 8 characters long.";
            } elseif ($new_password !== $confirm_password) {
                $error = "New password and confirm password do not match.";
            } else {
                // Update password in the database
                $update_sql = "UPDATE users SET password = '$new_password' WHERE email = '$email'";

                if ($conn->query($update_sql) === TRUE) {
                    echo "<script>alert('Password updated successfully.');</script>";
                  
                } else {
                    $error = "Error updating password: " . $conn->error;
                }
            }
        }
    } else {
        $error = "User not found.";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="./change_password.css">
    <style>
      
body, h1, h2, p, input, select, button {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Login container styling */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Login box styling */
.login-box {
    width: 400px; 
    padding: 40px; 
    background: rgba(255, 255, 255, 0.6);
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    text-align: center;
}

.login-box h2 {
    margin-bottom: 30px;
    color: #007bff;
}

/* User box styling */
.user-box {
    position: relative;
    margin-bottom: 20px;
    text-align: left; 
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
.user-box label {
    position: absolute;
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 5px;
    color: #000000;
    font-size: 14px;
    pointer-events: none;
    transition: top 0.3s, font-size 0.3s, color 0.3s;
}

.user-box input {
    width: calc(100% - 24px); 
    padding: 12px;
    font-size: 16px;
    color: #333;
    border: 1px solid #ccc;
    border-radius: 5px;
    outline: none;
    transition: border-color 0.3s;
}

.user-box input:focus {
    border-color: #007bff;
}

/* Button styling */
button[type="submit"] {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Error message styling */
.error {
    color: red;
    margin-top: 10px;
}

    </style>
</head>
<body>
<div class="login-container">
        <div class="login-box">
            <h2>Change Password</h2>
            <form action="" method="POST" onsubmit="return validateForm()">
                <div class="user-box">
                    <label for="old_password">Old Password</label>
                    <input type="password" id="old_password" name="old_password" required>
                </div>
                <div class="user-box">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required oninput="checkPasswordStrength()">
                    <div id="password-strength"></div>
                </div>
                <div class="user-box">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Change Password</button>
            </form>
            <div class="error"><?php if (isset($error)) echo $error; ?></div>
            <br><br><center><a href="./dashboard.php" class="back-button">Back to Dashboard</a></center>
        </div>
    </div>
    
    <script>
        function validateForm() {
            var oldPassword = document.getElementById("old_password").value;
            var newPassword = document.getElementById("new_password").value;
            var confirmPassword = document.getElementById("confirm_password").value;
            var error = "";

            // Validate password length
            if (newPassword.length < 8) {
                error = "Password must be at least 8 characters long.";
            }

            // Validate new password and confirm password match
            if (newPassword !== confirmPassword) {
                error = "New password and confirm password do not match.";
            }

            if (error) {
                document.querySelector(".error").innerText = error;
                return false;
            }

            return true;
        }

        function checkPasswordStrength() {
    var password = document.getElementById("new_password").value;
    var strength = document.getElementById("password-strength");

    var weakRegex = /^[a-z]+$/;
    var mediumRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/;
    var strongRegex = /^(?=.*\d)(?=.*[@$!%*?&]).+$/;

    if (strongRegex.test(password)) {
        strength.innerHTML = '<span style="color:green">Strong Password</span>';
    } else if (mediumRegex.test(password)) {
        strength.innerHTML = '<span style="color:orange">Medium Password</span>';
    } else if (weakRegex.test(password)) {
        strength.innerHTML = '<span style="color:red">Weak Password</span>';
    } 
}
    </script>
</body>
</html>
