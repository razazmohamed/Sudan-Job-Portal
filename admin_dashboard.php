<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
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

// Fetch users, employers, and jobs for overview
$user_sql = "SELECT * FROM Users";
$user_result = $conn->query($user_sql);

$employer_sql = "SELECT * FROM Employers";
$employer_result = $conn->query($employer_sql);

$job_sql = "SELECT * FROM Jobs";
$job_result = $conn->query($job_sql);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>لوحة تحكم الإدارة</h1>
        <nav>
            <ul>
                <li><a href="admin_logout.php">تسجيل خروج</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>إدارة المستخدمين</h2>
            <table>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الهاتف</th>
                    <th>السيرة الذاتية</th>
                    <th>إجراءات</th>
                </tr>
                <?php while ($user = $user_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($user['resume']); ?>">عرض السيرة الذاتية</a></td>
                    <td>
                        <a href="delete_user.php?user_id=<?php echo $user['user_id']; ?>">حذف</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section>
            <h2>إدارة أصحاب العمل</h2>
            <table>
                <tr>
                    <th>اسم الشركة</th>
                    <th>البريد الإلكتروني</th>
                    <th>الهاتف</th>
                    <th>إجراءات</th>
                </tr>
                <?php while ($employer = $employer_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($employer['company_name']); ?></td>
                    <td><?php echo htmlspecialchars($employer['email']); ?></td>
                    <td><?php echo htmlspecialchars($employer['phone']); ?></td>
                    <td>
                        <a href="delete_employer.php?employer_id=<?php echo $employer['employer_id']; ?>">حذف</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>

        <section>
            <h2>إدارة الوظائف</h2>
            <table>
                <tr>
                    <th>عنوان الوظيفة</th>
                    <th>الوصف</th>
                    <th>صاحب العمل</th>
                    <th>إجراءات</th>
                </tr>
                <?php while ($job = $job_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($job['job_description']); ?></td>
                    <td><?php echo htmlspecialchars($job['employer_id']); ?></td>
                    <td>
                        <a href="delete_job.php?job_id=<?php echo $job['job_id']; ?>">حذف</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 بوابة السودان للتوظيف الالكتروني. جميع الحقوق محفوظة.</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
