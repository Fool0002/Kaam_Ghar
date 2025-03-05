<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal Landing Page</title>
    <style>
        body, h1, h2, p, ul, li, a, img {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e5e5;
            padding: 20px 0;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo a {
            text-decoration: none;
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }

        .auth-links {
            display: flex;
            align-items: center;
        }

        .auth-links a {
            text-decoration: none;
            margin-left: 15px;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .auth-links .login-btn {
            color: #333;
            border: 1px solid #333;
        }

        .auth-links .signup-btn {
            color: #fff;
            background-color: #333;
        }

        .auth-links .login-btn:hover {
            background-color: #f8f9fa;
        }

        .auth-links .signup-btn:hover {
            background-color: #555;
        }

        .hero {
            text-align: center;
            padding: 60px 0;
        }

        .hero h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            color: #666;
        }

        .hero .highlight {
            background-color: #ffde59;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .cta-buttons {
            margin: 20px 0;
        }

        .cta-buttons a {
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 16px;
            margin: 0 10px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #333;
        }

        .btn-secondary:hover {
            background-color: #e2e6ea;
        }

        .hero-image {
            margin-top: 40px;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .hot-jobs {
            padding: 40px 0;
        }

        .hot-jobs h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            color: #333;
        }

        .job-listing-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        
    }

    .job-listing {
        background-color: #ffffff;
        border: 1px solid red;
        border-radius: 10px;
        padding: 15px;
        flex-basis: calc(25%); 
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
        margin-bottom: 10px;
        margin-right: 50px;
        
    }

        .job-listing:hover {
            box-shadow: 0 0 15px blue;
        }

        .job-listing h4 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #007BFF;
        }

        .job-listing p {
            margin: 5px 0;
            color: #666666;
        }

        .job-listing a {
            text-decoration: none;
            color: #007BFF;
            display: inline-block;
            margin-top: 10px;
        }

        .job-listing a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="#">KaamGhar</a>
                </div>
                <div class="auth-links">
                    <a href="./Authentication/login.php" class="login-btn">Log In</a>
                    <a href="./Authentication/signup.php" class="signup-btn">Sign Up</a>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="hero">
                <h1>Find a skilled <span class="highlight">Individual</span> to do the job</h1>
                <p>We can build new digital products from the ground up, or perform any other work.</p>
                <div class="cta-buttons">
                    <a href="./Authentication/signup.php" class="btn-primary">Get Started</a>
                </div>
                <div class="hero-image">
                    <img src="./people.webp" alt="Hero Image">
                </div>
            </div>
            <div class="hot-jobs">
                <h2><u>Available Jobs</u></h2>
                <div class="job-listing-container">
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "oddjob";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch hot jobs
                    $hot_jobs_query = "SELECT * FROM jobs WHERE status = 'approved' AND del = 'false' LIMIT 12";
                    $hot_jobs_result = $conn->query($hot_jobs_query);

                    if ($hot_jobs_result && $hot_jobs_result->num_rows > 0) {
                        while ($job = $hot_jobs_result->fetch_assoc()) {
                            echo '<div class="job-listing">';
                            echo '<h4>' . $job['job_title'] . '</h4>';
                            echo '<p><strong>Company Name:</strong> ' . $job['company_name'] . '</p>';
                            echo '<p><strong>Required Experience:</strong> ' . ($job['required_experience'] == 0 ? "No Experience Required." : $job['required_experience'] . " Years") . '</p>';
                            echo '<a href="./Authentication/login.php">Apply</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No hot jobs available at the moment.</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 KaamGhar. All rights reserved.</p>
            <p>Email: KaamGhar@gmail.com</p>
            <p>Phone: 01-422764</p>
        </div>
    </footer>
</body>
</html>
