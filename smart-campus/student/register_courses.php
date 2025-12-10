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
   FETCH STUDENT INFORMATION
============================= */
$stmt = $conn->prepare("SELECT id, first_name, last_name, reg_no FROM students WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    session_destroy();
    header("Location: login.php");
    exit();
}

/* =============================
   FETCH ALL COURSES BY SCHOOL
============================= */
$courses = [];
$res = $conn->query("SELECT id, course_name, school FROM courses ORDER BY school, course_name");
while ($row = $res->fetch_assoc()) {
    $courses[$row["school"]][] = $row;
}

/* ====================================
   FETCH COURSES STUDENT ALREADY REGISTERED
==================================== */
$registered = [];
$chk = $conn->prepare("SELECT course_id FROM course_registrations WHERE student_id=?");
$chk->bind_param("i", $student_id);
$chk->execute();
$regRes = $chk->get_result();
while ($r = $regRes->fetch_assoc()) {
    $registered[] = $r["course_id"];
}
$chk->close();

/* =============================
   HANDLE REGISTRATION POST
============================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['course_ids'])) {
        $_SESSION['error'] = "You must select at least one course.";
        header("Location: register_courses.php");
        exit();
    }

    foreach ($_POST['course_ids'] as $cid) {
        // prevent duplicate insert
        if (in_array($cid, $registered)) continue;

        $stmt = $conn->prepare("INSERT INTO course_registrations (student_id, course_id) VALUES (?,?)");
        $stmt->bind_param("ii", $student_id, $cid);
        $stmt->execute();
        $stmt->close();
    }

    $_SESSION['success'] = "Courses registered successfully!";
    header("Location: register_courses.php");
    exit();
}

$customCss = SITE_URL . "/assets/css/register_courses.css";
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo $customCss; ?>">

<div class="container digital-container">
    <h2>Register for Courses</h2>
    <p>Student: <strong><?php echo htmlspecialchars($student['first_name'] . " " . $student['last_name']); ?></strong></p>
    <p>Reg No: <strong><?php echo $student['reg_no']; ?></strong></p>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="digital-form">

        <?php foreach ($courses as $school => $list): ?>
            <div class="school-block">
                <h4><?php echo htmlspecialchars($school); ?></h4>

                <?php foreach ($list as $c): ?>
                    <label class="course-option">
                        <input 
                            type="checkbox" 
                            name="course_ids[]" 
                            value="<?php echo $c['id']; ?>"
                            <?php echo in_array($c['id'], $registered) ? "checked disabled" : ""; ?>
                        >
                        <?php echo htmlspecialchars($c['course_name']); ?>
                        <?php if (in_array($c['id'], $registered)): ?>
                            <span class="already-registered">(Already Registered)</span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Submit Registration</button>
    </form>

        <a href="../public/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<!-- =============================
     Collapsible Schools JS
============================= -->
<script>
document.querySelectorAll('.school-block h4').forEach(header => {
    header.addEventListener('click', () => {
        const parent = header.parentElement;
        parent.classList.toggle('collapsed');

        parent.querySelectorAll('.course-option').forEach(course => {
            course.style.display = parent.classList.contains('collapsed') ? 'none' : 'flex';
        });
    });
});

// Initially collapse all course lists
document.querySelectorAll('.school-block').forEach(block => {
    block.classList.add('collapsed');
    block.querySelectorAll('.course-option').forEach(course => {
        course.style.display = 'none';
    });
});
</script>
