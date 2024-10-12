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

// Check if employer is logged in
if (!isset($_SESSION['employer_email'])) {
    header("Location: employer_login.php");
    exit();
}

// Fetch employer ID based on the logged-in email
$sql = "SELECT id FROM Employers WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['employer_email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $employer = $result->fetch_assoc();
    $employer_id = $employer['id'];
} else {
    die("خطأ: لم يتم العثور على صاحب العمل.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insert job into the database
    $sql = "INSERT INTO Jobs (title, description, employer_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $description, $employer_id);
    
    if ($stmt->execute()) {
        echo "تم نشر الوظيفة بنجاح!";
    } else {
        echo "حدث خطأ أثناء نشر الوظيفة: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نشر وظيفة جديدة</title>
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
            max-width: 600px;
            margin: 100px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border 0.3s;
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #6200ea;
        }
        .btn {
            width: 100%;
            background-color: #6200ea;
            color: white;
            padding: 10px;
            border: none;
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
    <h1>نشر وظيفة جديدة</h1>
    <form method="POST" action="post_job.php">
        <div class="form-group">
            <label for="title">عنوان الوظيفة:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">وصف الوظيفة:</label>
            <textarea id="description" name="description" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn">نشر الوظيفة</button>
    </form>

    <footer>
        <p><a href="employer_dashboard.php">العودة إلى لوحة التحكم</a></p>
        <p><a href="index.php">العودة إلى الصفحة الرئيسية</a></p>
    </footer>
</div>

</body>
</html>
