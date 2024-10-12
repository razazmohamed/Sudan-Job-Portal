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

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$job_id = $_GET['id'];
$sql = "SELECT * FROM Jobs WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$job = $result->fetch_assoc();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الوظيفة</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .job-detail {
            margin-bottom: 20px;
        }
        .job-detail label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .job-detail p {
            margin: 0 0 15px;
            color: #555;
        }
        .btn {
            background-color: #6200ea;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #3700b3;
        }
        footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>تفاصيل الوظيفة</h1>

    <div class="job-detail">
        <label>المسمى الوظيفي:</label>
        <p><?php echo htmlspecialchars($job['title']); ?></p>
    </div>

    <div class="job-detail">
        <label>الوصف:</label>
        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
    </div>

    <div class="job-detail">
        <label>تاريخ النشر:</label>
        <p><?php echo date("Y-m-d", strtotime($job['created_at'])); ?></p>
    </div>

    <a href="apply_job.php?id=<?php echo $job['id']; ?>" class="btn">تقديم طلب</a>
    <a href="index.php" class="btn">العودة إلى الصفحة الرئيسية</a>
</div>

<footer>
    <p>&copy; 2024 بوابة السودان للتوظيف الالكتروني</p>
</footer>

</body>
</html>
