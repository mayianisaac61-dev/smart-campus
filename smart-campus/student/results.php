<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php"; // SITE_URL

// Auth check
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student info and result_text, also check if result is uploaded
$stmt = $conn->prepare("SELECT first_name, last_name, reg_no, result_text, result_uploaded FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    $_SESSION['error'] = "Student not found!";
    header("Location: login.php");
    exit();
}

// If result slip is not uploaded, block viewing results
if ($student['result_uploaded'] == 0) {
    $_SESSION['error'] = "You cannot view results until you upload your result slip.";
    header("Location: upload_results.php");
    exit();
}

// Parse result_text (stored as JSON)
$results = [];
if (!empty($student['result_text'])) {
    $results = json_decode($student['result_text'], true);
}
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/results.css">

<div class="container digital-container">
    <h2>My Results</h2>
    <p><strong>Student:</strong> <?php echo htmlspecialchars($student['first_name'] . " " . $student['last_name']); ?></p>
    <p><strong>Reg No:</strong> <?php echo htmlspecialchars($student['reg_no']); ?></p>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <table class="results-table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Grade</th>
                    <th>GPA</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td data-label="Course Code"><?php echo htmlspecialchars($row['code']); ?></td>
                        <td data-label="Course Title"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td data-label="Grade"><?php echo htmlspecialchars($row['grade']); ?></td>
                        <td data-label="GPA"><?php echo htmlspecialchars($row['gpa']); ?></td>
                        <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results uploaded yet. Please upload your result slip first.</p>
    <?php endif; ?>

    <div class="mt-4">
        <a href="../student/upload_results.php" class="btn btn-primary">Upload Result Slip</a>
        <a href="../public/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
