<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
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

// Initialize variables
$new_email = '';
$new_role = '';
$user_id = 0; 

// Fetch existing user details if user_id is set
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT email, role FROM users WHERE id = $user_id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_email = $row['email'];
        $new_role = $row['role'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        $new_email = $_POST['new_email'];
        $new_role = $_POST['new_role'];

        // Update user details in the database
        $sql = "UPDATE users SET email='$new_email', role='$new_role' WHERE id=$user_id";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Record Updated Successfully');</script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "User ID is not set.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, select:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-button {
            background-color: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #495057;
        }
    </style>
</head>
<body>
    <h1>Edit User</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
        <label for="new_email">New Email:</label>
        <input type="text" id="new_email" name="new_email" value="<?php echo htmlspecialchars($new_email); ?>" required>
        <label for="new_role">New Role:</label>
        <select id="new_role" name="new_role" required>
            <option value="employer" <?php echo ($new_role == 'employer') ? 'selected' : ''; ?>>Employer</option>
            <option value="jobseeker" <?php echo ($new_role == 'jobseeker') ? 'selected' : ''; ?>>Job Seeker</option>
        </select>
        <button type="submit">Update User</button><br>
      
        
    </form>
 <center> <a class="back-button" href="./admin.php">Dashboard</a></center>  
</body>
</html>
