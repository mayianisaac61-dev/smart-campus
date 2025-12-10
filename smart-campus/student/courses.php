<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

/* =============================
   FETCH REGISTERED COURSES
============================= */
$stmt = $conn->prepare("
    SELECT c.course_code, c.course_name, c.instructor
    FROM course_registrations cr
    JOIN courses c ON cr.course_id = c.id
    WHERE cr.student_id = ?
    ORDER BY c.school, c.course_name
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$registered_courses = [];
while ($row = $result->fetch_assoc()) {
    $registered_courses[] = $row;
}
$stmt->close();
?>

<?php include __DIR__ . "/../includes/header.php"; ?>

<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/courses.css">

<div class="container">
    <h2>My Courses</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="courses-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Instructor</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($registered_courses) > 0): ?>
                <?php foreach ($registered_courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                        <td><?php echo htmlspecialchars($course['instructor'] ?? 'TBA'); ?></td>
                        <td>Enrolled</td>
                        <td>
                            <a href="view_course.php?id=<?php echo $course['course_code']; ?>">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">You have not registered for any courses yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
