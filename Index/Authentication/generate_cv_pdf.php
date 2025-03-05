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

session_start();
$email = $_SESSION['email'];

// Check if an 'id' parameter is provided in the URL
if (isset($_GET['cv_id'])) {
    $id = $_GET['cv_id'];

    // Query to retrieve CV data based on ID and email
    $sql = "SELECT * FROM cv WHERE id = '$id' OR email = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch data and display it
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
        echo "No CV found for this ID and email combination.";
        $conn->close();
        exit(); // Exit if no CV found
    }
} else {
    echo "No ID provided.";
    $conn->close();
    exit(); // Exit if no ID is provided
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Details</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding: 20px;
        }
        .cv-container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .cv-header {
            display: flex;
            width: 100%;
            flex-direction: row;
            
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .cv-picture {
            max-width: 200px;
    max-height: 150px;
    border-radius: 10%;
    position: absolute;
    left:900px; 
        }
        .cv-details {
            flex: 1;
        }
        .cv-details h2 {
            margin-bottom: 10px;
        }
        .cv-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .cv-section h2 {
            margin-bottom: 10px;
        }
        .cv-skills {
            width: 100%;
            margin-bottom: 20px;
        }
        .cv-skills ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            padding-left: 20px; 
        }
        .cv-skills ul li {
            margin-bottom: 5px;
        }
        .update-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .update-link:hover {
            background-color: #008000; 
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
       
        @media (max-width: 768px) {
            .cv-header {
                flex-direction: column;
            }
            .cv-picture {
                margin: 0 auto 20px;
            }
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
        .print-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .print-button:hover {
            background-color: #0056b3;
        }
        @media print {
    body {
        background-color: #fff;
    }
    .cv-container {
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
    .cv-header {
        flex-direction: row !important;
        align-items: flex-start !important;
    }
    .cv-picture {
        position: absolute;
        margin-right: 20px !important;
        top: 50px !important;
        left: 500px !important;
    }
    .update-link, .back-button {
        display: none;
    }
}
    </style>
    <script>
        function printCV() {
            var printContents = document.querySelector('.cv-container').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
</head>
<body>
    <div class="cv-container">
        <div class="cv-header">
            <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="cv-picture">
            <div class="cv-details">
                <h2>Name: <?php echo $fname . ' ' . $lname; ?></h2>
                <p><strong>Age:</strong> <?php echo $age; ?></p>
                <p><strong>Gender:</strong> <?php echo $gender; ?></p>
            </div>
        </div>

        <div class="cv-section">
            <h2>Work Experience</h2>
            <table>
                <tbody>
                    <tr>
                        <td><strong>Work Field:</strong></td>
                        <td><?php echo $job_title; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Experience:</strong></td>
                        <td><?php echo $experience == 0 ? "No Experience" : $experience . " years"; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cv-section">
            <h2>Education</h2>
            <table>
                <thead>
                    <tr>
                        <th>Education</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Degree:</strong></td>
                        <td><?php echo $degree; ?></td>
                    </tr>
                    <tr>
                        <td><strong>University:</strong></td>
                        <td><?php echo $university; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Graduation Year:</strong></td>
                        <td><?php echo $grad_year; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="cv-skills">
            <h2>Skills:</h2>
            <ul style="list-style-type:disc">
                <?php
                $skills_list = explode(',', $skills);
                foreach ($skills_list as $skill) {
                    echo "<li>$skill</li>";
                }
                ?>
            </ul>
        </div>

        
    </div>
    <center><button class="print-button" onclick="printCV()">Print CV</button></center><br>
    <?php if ($_SESSION['role']=="admin"){
 echo   '<center><a href="./admin.php" class="back-button">Back to Dashboard</a></center>';
}?>


</body>
</html>
