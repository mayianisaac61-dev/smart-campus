<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php";

// Path to page-specific CSS
$customCss = SITE_URL . "/assets/css/students.css";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $password   = trim($_POST['password']);

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $_SESSION['error'] = "All required fields must be filled!";
        header("Location: add_student.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: add_student.php");
        exit();
    }

    // Check if email already exists in `students` table
    $check = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();
    if ($check_result->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: add_student.php");
        exit();
    }
    $check->close();

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new student into `students` table
    $stmt = $conn->prepare("INSERT INTO students (first_name, last_name, email, phone, password, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Student added successfully!";
        header("Location: managestudents.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: add_student.php");
        exit();
    }
    $stmt->close();
}
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo $customCss; ?>">

<div class="container mt-4">
    <h2>Add New Student</h2>

    <!-- Display messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="add_student.php" method="POST" class="mt-3">
        <div class="mb-3">
            <label>First Name:</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Last Name:</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone:</label>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
        <a href="managestudents.php" class="btn btn-secondary">Back</a>
    </form>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
