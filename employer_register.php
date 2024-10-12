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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $sql = "INSERT INTO Employers (company_name, email, phone, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $company_name, $email, $phone, $hashed_password);
    $stmt->execute();

    echo "تم التسجيل بنجاح!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل أصحاب العمل</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            padding: 50px;
            direction: rtl;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #3f51b5;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: border 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #3f51b5;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #3f51b5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #5c6bc0;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .button {
            text-decoration: none;
            padding: 10px 20px;
            border: 1px solid #3f51b5;
            color: #3f51b5;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #3f51b5;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تسجيل أصحاب العمل</h1>
        <form method="POST">
            <label for="company_name">اسم الشركة:</label>
            <input type="text" name="company_name" required>
            <label for="email">البريد الإلكتروني:</label>
            <input type="email" name="email" required>
            <label for="phone">رقم الهاتف:</label>
            <input type="text" name="phone" required>
            <label for="password">كلمة المرور:</label>
            <input type="password" name="password" required>
            <button type="submit">تسجيل</button>
        </form>
        <div class="buttons">
            <a href="employer_login.php" class="button">تسجيل دخول</a>
            <a href="index.php" class="button">العودة إلى الصفحة الرئيسية</a>
        </div>
    </div>
</body>
</html>
