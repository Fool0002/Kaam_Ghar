<?php
$password = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "oddjob";

    $conn = new mysqli($servername, $username, $password_db, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $username_input = $_POST['username'];

    $sql = "SELECT password,del FROM users WHERE email = '$email' AND name = '$username_input'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['del']=='false'){

            $password = $row['password'];
        }
        else
        {
            $error = "Invalid email or username.";
        }
    } else {
        $error = "Invalid email or username.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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

        /* Header styling */
        header {
            text-align: center;
            margin-bottom: 20px;
        }

        form h1 {
            color: #007bff;
        }

        /* Main section styling */
        main {
            text-align: center;
        }

        /* Form styling */
        form {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 600px;
            margin: 0 auto;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            color: #000000;
        }

        form input {
            width: calc(100% - 24px); /* Adjusted for padding */
            padding: 12px;
            font-size: 16px;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            margin-bottom: 20px;
            transition: border-color 0.3s;
        }

        form input:focus {
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
        p.error {
            color: red;
            margin-top: 10px;
        }

        /* Links styling */
        p a {
            color: #000000;
            text-decoration: none;
            font-size: 14px;
        }

        p a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        
    </header>
    <main>
        <form action="" method="POST">
            <h1>Forgot Password</h1>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <button type="submit">Retrieve Password</button>
            <br>
            <p style="text-align: center;"><a href="./login.php">Back to Login</a></p>
            <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?>
        </form>

        <!-- Modal to display password -->
        <div id="passwordModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p>Your password is: <?php if (!empty($password)) { echo $password; } ?></p>
            </div>
        </div>

        <script>
           
            var modal = document.getElementById("passwordModal");

            
            function openModal() {
                modal.style.display = "block";
            }

            
            function closeModal() {
                modal.style.display = "none";
            }

            
            <?php if (!empty($password)) { echo "openModal();"; } ?>
        </script>
    </main>
</body>
</html>
