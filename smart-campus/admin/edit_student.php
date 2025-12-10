<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php"; // For SITE_URL

// Get student ID from query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: managestudents.php");
    exit();
}

$student_id = intval($_GET['id']);

// Fetch student details from `students` table
$stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    header("Location: managestudents.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_student'])) {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);

    if (empty($first_name) || empty($last_name) || empty($email)) {
        $error = "First Name, Last Name, and Email are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        $update = $conn->prepare("UPDATE students SET first_name=?, last_name=?, email=?, phone=? WHERE id=?");
        $update->bind_param("ssssi", $first_name, $last_name, $email, $phone, $student_id);
        if ($update->execute()) {
            $_SESSION['success'] = "Student updated successfully!";
            header("Location: managestudents.php");
            exit();
        } else {
            $error = "Error updating student: " . $update->error;
        }
        $update->close();
    }
}
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/dashboard.css">

<div class="container mt-4">
    <h2>Edit Student</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($student['first_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($student['last_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($student['phone']); ?>">
        </div>
        <button type="submit" name="update_student" class="btn btn-primary">Update Student</button>
        <a href="managestudents.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
