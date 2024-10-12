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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update profile information
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $skills = $_POST['skills'];
    $resume_path = $user['resume']; // Keep the existing resume path if no new upload

    // Handle resume upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $file_tmp = $_FILES['resume']['tmp_name'];
        $file_name = basename($_FILES['resume']['name']);
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'jpeg', 'jpg'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $resume_path = 'uploads/' . $file_name;
            move_uploaded_file($file_tmp, $resume_path);
        }
    }

    // Update user information
    $update_sql = "UPDATE Users SET name=?, phone=?, skills=?, resume=? WHERE email=?";
    $stmt = $conn->prepare($update_sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $phone, $skills, $resume_path, $email);
        $stmt->execute();
        $stmt->close();
    }

    // Update password if provided
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $update_password_sql = "UPDATE Users SET password=? WHERE email=?";
        $stmt_password = $conn->prepare($update_password_sql);
        
        if ($stmt_password) {
            $stmt_password->bind_param("ss", $new_password, $email);
            $stmt_password->execute();
            $stmt_password->close();
        }
    }

    header("Location: user_profile.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف المستخدم</title>
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
            max-width: 600px;
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
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            border: 1px solid #007bff;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            flex: 1;
            margin: 0 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        .btn:hover {
            background-color: #007bff;
            color: white;
        }
        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>ملف المستخدم</h1>
        </header>

        <form method="post" action="user_profile.php" enctype="multipart/form-data">
            <label for="name">الاسم:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="phone">رقم الهاتف:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">

            <label for="skills">المهارات:</label>
            <textarea id="skills" name="skills" rows="4"><?php echo htmlspecialchars($user['skills']); ?></textarea>

            <label for="resume">رفع السيرة الذاتية:</label>
            <input type="file" id="resume" name="resume" accept=".pdf,.jpeg,.jpg">

            <label for="new_password">كلمة المرور الجديدة:</label>
            <input type="password" id="new_password" name="new_password" placeholder="أدخل كلمة مرور جديدة">

            <button type="submit">تحديث المعلومات</button>
        </form>

        <div class="btn-container">
            <a href="user_dashboard.php" class="btn">رجوع</a>
            <a href="index.php" class="btn">العودة إلى الصفحة الرئيسية</a>
        </div>
    </div>

    <footer>
        <p><a href="index.php">العودة إلى الصفحة الرئيسية</a></p>
    </footer>
</body>
</html>
