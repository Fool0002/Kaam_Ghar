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

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT id, password, role,name,del FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($pass === $row['password']&&$row['del']=='false') {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['name'];
            if ($_SESSION['role'] === 'admin') {
                header("Location: admin.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            $error = "Email or password doesn't exist in the system.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
    background: url('./forgotpass.jpg') no-repeat center center fixed;
    background-size: cover;
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
    width: 600px; 
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
    width: calc(100% - 24px); /* Adjusted for padding */
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

/* Links styling */
.links {
    margin-top: 20px;
}

.links a {
    color: #000000;
    text-decoration: none;
    font-size: 14px;
}

.links a:hover {
    text-decoration: underline;
    color: #0056b3;
}

    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <form action="login.php" method="POST" onsubmit="return validateForm()">
                <div class="user-box">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="user-box">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="error"><?php if (isset($error)) echo $error; ?></div>
            <div class="links">
                <a href="signup.php">Register</a>
                <span>&nbsp;|&nbsp;</span>
                <a href="./forgotpass.php">Forgot Password?</a>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var error = "";

            // Validate email format
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                error = "Invalid email address.";
            }

            // Validate password length
            if (password.length < 8) {
                error = "Password must be at least 8 characters long.";
            }

            if (error) {
                document.querySelector(".error").innerText = error;
                return false;
            }

            return true;
        }
    </script>
</body>
</html>
