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

// Get the job ID from the POST request
$job_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0;

// Handle resume upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    $resume = $_FILES['resume'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($resume['name']);

    // Check file type
    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    if ($fileType != 'pdf' && $fileType != 'jpeg' && $fileType != 'jpg') {
        die("فقط ملفات PDF أو JPEG مسموح بها.");
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($resume['tmp_name'], $uploadFile)) {
        // Prepare SQL to insert application
        $stmt = $conn->prepare("INSERT INTO Applications (job_id, user_email, resume_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $job_id, $_SESSION['user_email'], $uploadFile);
        
        if ($stmt->execute()) {
            echo "تم تقديم الطلب بنجاح.";
        } else {
            echo "حدث خطأ أثناء تقديم الطلب: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "حدث خطأ أثناء رفع الملف.";
    }
} else {
    echo "يرجى رفع السيرة الذاتية.";
}

$conn->close();
?>
