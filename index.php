<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sudan_jobs_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch jobs to display on the homepage
$sql = "SELECT id, title, description FROM Jobs ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة السودان للتوظيف</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            direction: rtl;
        }
        .navbar {
            background-color: #0d47a1; /* Dark blue color */
            color: white;
            padding: 15px;
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 16px;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #ddd;
        }
        .container {
            display: flex;
            justify-content: space-between;
            padding: 80px 20px;
            margin-top: 60px;
        }
        .left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .left img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .right {
            flex: 2;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .job {
            background-color: #fafafa;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }
        .job h3 {
            margin: 0;
            font-size: 18px;
            color: #0d47a1;
        }
        .job p {
            font-size: 14px;
            color: #666;
        }
        .btn {
            background-color: #0d47a1;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            display: block;
            margin-top: 20px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0a356b;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-secondary {
            background-color: #9e9e9e;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #616161;
        }
    </style>
</head>
<body>

<!-- Navbar with navigation links -->
<div class="navbar">
    <h1>بوابة السودان للتوظيف</h1>
    <div>
        <a href="user_login.php">تسجيل دخول المستخدم</a>
        <a href="employer_login.php">تسجيل دخول صاحب العمل</a>
        <a href="user_register.php">تسجيل المستخدم</a>
        <a href="employer_register.php">تسجيل صاحب العمل</a>
    </div>
</div>

<div class="container">
    <!-- Left section for the image -->
    <div class="left">
        <img src="online_job_portal.png" alt="Job Image">
    </div>

    <!-- Right section for content -->
    <div class="right">
        <h2>أحدث الوظائف</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($job = $result->fetch_assoc()): ?>
                <div class="job">
                    <h3><?php echo $job['title']; ?></h3>
                    <p><?php echo substr($job['description'], 0, 100) . '...'; ?></p>
                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="btn">عرض التفاصيل</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>لا توجد وظائف متاحة حالياً.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
$conn->close();
?>
