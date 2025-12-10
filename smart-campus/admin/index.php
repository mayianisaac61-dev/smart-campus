<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php"; // For SITE_URL

// Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

// Get current user
$user_id = $_SESSION['user_id'];
$query = "SELECT first_name, last_name, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) { die("Prepare failed: " . $conn->error); }
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$currentUser = $result->fetch_assoc();
$stmt->close();

// Ensure admin role
if ($currentUser['role'] !== 'admin') {
    header("Location: ../public/dashboard.php");
    exit();
}

// Path to admin CSS
$customCss = SITE_URL . "/assets/css/admin.css";
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo $customCss; ?>">

<div class="container mt-4">
    <h2>Welcome, <?php echo htmlspecialchars($currentUser['first_name'] . " " . $currentUser['last_name']); ?> 👋</h2>
    <p>You are logged in as <strong>Admin</strong>.</p>
    <p><?php echo "Today is " . date("l, F j, Y"); ?></p>

    <div class="row mt-4">
        <!-- Students -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>👨‍🎓 Students</h5>
                <a href="managestudents.php" class="btn btn-primary btn-sm mt-2">Manage Students</a>
            </div>
        </div>

        <!-- Staff -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>👩‍🏫 Staff</h5>
                <a href="staff.php" class="btn btn-primary btn-sm mt-2">Manage Staff</a>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>📚 Courses</h5>
                <a href="courses.php" class="btn btn-primary btn-sm mt-2">Manage Courses</a>
            </div>
        </div>

        <!-- Exams/Results -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>📝 Exams</h5>
                <a href="exams.php" class="btn btn-primary btn-sm mt-2">Manage Exams</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Attendance -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>📅 Attendance</h5>
                <a href="attendance.php" class="btn btn-primary btn-sm mt-2">View/Mark Attendance</a>
            </div>
        </div>

        <!-- Fees -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>💳 Fees</h5>
                <a href="fees.php" class="btn btn-primary btn-sm mt-2">Manage Fees</a>
            </div>
        </div>

        <!-- Library -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>📖 Library</h5>
                <a href="library.php" class="btn btn-primary btn-sm mt-2">Manage Library</a>
            </div>
        </div>

        <!-- Notices -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>📢 Notices</h5>
                <a href="notices.php" class="btn btn-primary btn-sm mt-2">Post/View Notices</a>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Reports -->
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>📊 Reports</h5>
                <a href="reports.php" class="btn btn-primary btn-sm mt-2">Analytics & Reports</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
