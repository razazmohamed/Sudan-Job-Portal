<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: user_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sudan_jobs_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$email = $_SESSION['user_email'];
$sql = "SELECT * FROM Users WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch job listings
$jobs_sql = "SELECT * FROM Jobs";
$jobs_result = $conn->query($jobs_sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #3f51b5;
            margin-bottom: 20px;
        }
        .job-list {
            margin-top: 20px;
        }
        .job-item {
            background: #e3f2fd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .job-title {
            font-weight: 500;
            color: #3f51b5;
        }
        .apply-button {
            background-color: #3f51b5;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .apply-button:hover {
            background-color: #5c6bc0;
        }
        .buttons {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .back-button, .main-button {
            text-decoration: none;
            padding: 10px 20px;
            border: 1px solid #3f51b5;
            color: #3f51b5;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .back-button:hover, .main-button:hover {
            background-color: #3f51b5;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>لوحة التحكم</h1>
        <div class="job-list">
            <?php if ($jobs_result->num_rows > 0): ?>
                <?php while($job = $jobs_result->fetch_assoc()): ?>
                    <div class="job-item">
                        <h2 class="job-title"><?php echo htmlspecialchars($job['title']); ?></h2>
                        <p><?php echo htmlspecialchars($job['description']); ?></p>
                        <a href="apply_job.php?job_id=<?php echo $job['id']; ?>" class="apply-button">تقدم للوظيفة</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>لا توجد وظائف متاحة حالياً.</p>
            <?php endif; ?>
        </div>

        <div class="buttons">
            <a href="user_profile.php" class="back-button">الذهاب إلى ملف المستخدم</a>
            <a href="index.php" class="main-button">العودة إلى الصفحة الرئيسية</a>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
