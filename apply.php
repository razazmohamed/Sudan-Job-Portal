<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sudan_jobs_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the job ID from the URL
$job_id = isset($_GET['job_id']) ? intval($_GET['job_id']) : 0;

// Prepare SQL to fetch job details
$sql = "SELECT * FROM Jobs WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $job = $result->fetch_assoc();
} else {
    die("هذه الوظيفة غير موجودة.");
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقديم طلب لوظيفة</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f4f4f4;
            padding: 50px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تقديم طلب لوظيفة: <?php echo htmlspecialchars($job['title']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
        
        <form action="submit_application.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
            <div class="form-group">
                <label for="resume">رفع السيرة الذاتية (PDF أو JPEG):</label>
                <input type="file" class="form-control" name="resume" id="resume" required>
            </div>
            <button type="submit" class="btn btn-primary">تقديم الطلب</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
