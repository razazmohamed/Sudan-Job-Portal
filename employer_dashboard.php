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

// Fetch employer details
if (isset($_SESSION['employer_email'])) {
    $email = $_SESSION['employer_email'];
    $sql = "SELECT * FROM Employers WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $employer = $result->fetch_assoc();
    $stmt->close();
} else {
    header("Location: employer_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - صاحب العمل</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f4;
            direction: rtl;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .card {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            margin-top: 0;
            font-size: 20px;
            color: #007bff;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px 0;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>مرحبا بك في لوحة التحكم</h1>
        </header>
        
        <div class="card">
            <h2>بيانات صاحب العمل</h2>
            <p><strong>اسم الشركة:</strong> <?php echo htmlspecialchars($employer['company_name']); ?></p>
            <p><strong>البريد الإلكتروني:</strong> <?php echo htmlspecialchars($employer['email']); ?></p>
        </div>

        <div class="btn-container">
            <a href="post_job.php" class="btn">نشر وظيفة</a>
            <a href="employer_profile.php" class="btn">ملف التعريف</a>
            <a href="logout.php" class="btn">تسجيل الخروج</a>
        </div>
    </div>
</body>
</html>
