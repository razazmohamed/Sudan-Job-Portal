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

// Check if job_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['job_id'])) {
    $job_id = $_POST['job_id'];
    $user_email = $_SESSION['user_email'];

    // Get user ID from Users table
    $user_sql = "SELECT id FROM Users WHERE email=?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $user_result = $stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user = $user_result->fetch_assoc();
        $user_id = $user['id'];

        // Insert application into Applications table
        $apply_sql = "INSERT INTO Applications (user_id, job_id) VALUES (?, ?)";
        $apply_stmt = $conn->prepare($apply_sql);
        $apply_stmt->bind_param("ii", $user_id, $job_id);
        
        if ($apply_stmt->execute()) {
            echo "تم التقديم على الوظيفة بنجاح!";
        } else {
            echo "حدث خطأ أثناء التقديم على الوظيفة: " . $conn->error;
        }
    } else {
        echo "لم يتم العثور على معلومات المستخدم.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقديم الوظيفة</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="buttons">
        <a href="user_dashboard.php" class="back-button">عودة إلى لوحة التحكم</a>
        <a href="index.php" class="main-button">العودة إلى الرئيسية</a>
    </div>
</body>
</html>
