<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php"; // SITE_URL

// Auth check
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch current student
$student_id = $_SESSION['student_id'];

$stmt = $conn->prepare("SELECT id, first_name, last_name, reg_no FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$currentStudent = $result->fetch_assoc();
$stmt->close();

// If student not found, logout
if (!$currentStudent) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Page-specific CSS
$customCss = SITE_URL . "/assets/css/dashboard.css";
?>

<?php include __DIR__ . "/../includes/header.php"; ?>

<link rel="stylesheet" href="<?php echo $customCss; ?>">

<main class="flex-grow-1">
    <div class="container mt-4">
        <!-- Welcome Header -->
        <div class="welcome-header text-center">
            <h2>Welcome, <?php echo htmlspecialchars($currentStudent['first_name'] . " " . $currentStudent['last_name']); ?> 👋</h2>
            <p>Reg No: <strong><?php echo $currentStudent['reg_no']; ?></strong></p>
            <p><?php echo "Today is " . date("l, F j, Y"); ?></p>
        </div>

        <!-- Dashboard Menu -->
        <div class="dashboard-grid">

            <!-- Course Application -->
            <div class="dashboard-card">
                <h4>📚 Courses</h4>
                <ul>
                    <li><a href="../student/register_courses.php">Apply/Register Courses</a></li>
                    <li><a href="../student/courses.php">View My Courses</a></li>
                </ul>
            </div>

            <!-- Results Upload / Check -->
            <div class="dashboard-card">
                <h4>📝 Results</h4>
                <ul>
                    <li><a href="../student/upload_results.php">Upload Result Slips</a></li>
                    <li><a href="../student/results.php">Check My Results</a></li>
                </ul>
            </div>

            <!-- Attendance -->
            <div class="dashboard-card">
                <h4>📅 Attendance</h4>
                <ul>
                    <li><a href="../student/attendance.php">View Attendance</a></li>
                </ul>
            </div>

            <!-- Profile -->
            <div class="dashboard-card">
                <h4>👤 Profile</h4>
                <ul>
                    <li><a href="../student/profile.php">View/Edit Profile</a></li>
                    <li><a href="../student/change_password.php">Change Password</a></li>
                </ul>
            </div>

            <!-- Library -->
            <div class="dashboard-card">
                <h4>📖 Library</h4>
                <ul>
                    <li><a href="../student/library.php">My Library</a></li>
                    <li><a href="../student/library_resources.php">Resources</a></li>
                </ul>
            </div>

            <!-- Notices -->
            <div class="dashboard-card">
                <h4>📢 Notices</h4>
                <ul>
                    <li><a href="../student/notices.php">View Notices</a></li>
                </ul>
            </div>

            <!-- Logout -->
            <div class="dashboard-card logout-card">
                <h4>🚪 Logout</h4>
                <ul>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . "/../includes/footer.php"; ?>
