<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch some stats
$studentCount = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$adminCount = $conn->query("SELECT COUNT(*) as total FROM admins")->fetch_assoc()['total'];
$courseCount = $conn->query("SELECT COUNT(*) as total FROM courses")->fetch_assoc()['total'];
$examCount = $conn->query("SELECT COUNT(*) as total FROM exams")->fetch_assoc()['total'];
$staffCount = $conn->query("SELECT COUNT(*) as total FROM staff")->fetch_assoc()['total'];
$noticeCount = $conn->query("SELECT COUNT(*) as total FROM notices")->fetch_assoc()['total'];
$reportCount = $conn->query("SELECT COUNT(*) as total FROM reports")->fetch_assoc()['total'];
$attendanceCount = $conn->query("SELECT COUNT(*) as total FROM attendance")->fetch_assoc()['total'];
$feesCount = $conn->query("SELECT COUNT(*) as total FROM fees")->fetch_assoc()['total'];
$libraryCount = $conn->query("SELECT COUNT(*) as total FROM library")->fetch_assoc()['total'];
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin_dashboard.css">

<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</p>

    <!-- Stats Section -->
    <div class="dashboard-cards">
        <a href="managestudents.php" class="card">
            <h3>Total Students</h3>
            <p><?php echo $studentCount; ?></p>
        </a>
        <a href="admin_dashboard.php" class="card">
            <h3>Total Admins</h3>
            <p><?php echo $adminCount; ?></p>
        </a>
        <a href="courses.php" class="card">
            <h3>Total Courses</h3>
            <p><?php echo $courseCount; ?></p>
        </a>
        <a href="exams.php" class="card">
            <h3>Total Exams</h3>
            <p><?php echo $examCount; ?></p>
        </a>
        <a href="staff.php" class="card">
            <h3>Total Staff</h3>
            <p><?php echo $staffCount; ?></p>
        </a>
        <a href="notices.php" class="card">
            <h3>Total Notices</h3>
            <p><?php echo $noticeCount; ?></p>
        </a>
        <a href="reports.php" class="card">
            <h3>Total Reports</h3>
            <p><?php echo $reportCount; ?></p>
        </a>
        <a href="attendance.php" class="card">
            <h3>Total Attendance Records</h3>
            <p><?php echo $attendanceCount; ?></p>
        </a>
        <a href="fees.php" class="card">
            <h3>Total Fees Records</h3>
            <p><?php echo $feesCount; ?></p>
        </a>
        <a href="library.php" class="card">
            <h3>Total Library Records</h3>
            <p><?php echo $libraryCount; ?></p>
        </a>
    </div>

    <!-- Quick Action Links -->
    <div class="quick-links">
        <h3>Quick Actions</h3>
        <div class="links">
            <a href="add_student.php">Add Student</a>
            <a href="edit_student.php">Edit Student</a>
            <a href="delete_student.php">Delete Student</a>
        </div>
    </div>

    <a href="logout.php" class="btn-logout">Logout</a>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
