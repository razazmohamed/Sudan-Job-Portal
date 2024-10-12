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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update employer information
    $company_name = $_POST['company_name'];
    $new_email = $_POST['email'];
    
    // Update in the database
    $update_sql = "UPDATE Employers SET company_name=?, email=? WHERE email=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sss", $company_name, $new_email, $email);
    $update_stmt->execute();
    
    // Update session email if it was changed
    if ($new_email !== $email) {
        $_SESSION['employer_email'] = $new_email;
    }
    
    $update_stmt->close();
    header("Location: employer_profile.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف التعريف - صاحب العمل</title>
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
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
            <h1>ملف التعريف - صاحب العمل</h1>
        </header>
        
        <div class="card">
            <h2>معلومات الشركة</h2>
            <form method="POST" action="employer_profile.php">
                <label for="company_name">اسم الشركة:</label>
                <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($employer['company_name']); ?>" required>

                <label for="email">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employer['email']); ?>" required>

                <button type="submit" class="btn">تحديث المعلومات</button>
            </form>
        </div>

        <div class="btn-container">
            <a href="employer_dashboard.php" class="btn">العودة إلى لوحة التحكم</a>
            <a href="logout.php" class="btn">تسجيل الخروج</a>
        </div>
    </div>
</body>
</html>
