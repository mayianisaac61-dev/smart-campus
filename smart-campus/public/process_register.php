<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['register'])) {

    // ✅ 1. Collect & sanitize input
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name  = trim($_POST['last_name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? NULL;
    $gender = $_POST['gender'] ?? NULL;
    $course_id = $_POST['course_id'] ?? NULL;

    // ✅ 2. Required fields validation
    if ($first_name === '' || $last_name === '' || $email === '' || $phone === '' || $password === '' || $confirm_password === '') {
        $_SESSION['error'] = "All required fields must be filled!";
        header("Location: register.php");
        exit();
    }

    // ✅ 3. Gmail only validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $email)) {
        $_SESSION['error'] = "Only Gmail addresses are allowed!";
        header("Location: register.php");
        exit();
    }

    // ✅ 4. Phone validation (Kenyan format: 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        $_SESSION['error'] = "Phone number must be exactly 10 digits!";
        header("Location: register.php");
        exit();
    }

    // ✅ 5. Strong password validation
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=]).{8,}$/", $password)) {
        $_SESSION['error'] = "Password must be at least 8 characters with uppercase, lowercase, number & special character!";
        header("Location: register.php");
        exit();
    }

    // ✅ 6. Password match check
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }

    // ✅ 7. Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    // ✅ 8. Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ✅ 9. Generate unique registration number
    do {
        $reg_no = "SC-" . date("Y") . "-" . rand(10000, 99999);
        $check = $conn->prepare("SELECT id FROM students WHERE reg_no = ?");
        $check->bind_param("s", $reg_no);
        $check->execute();
        $check->store_result();
    } while ($check->num_rows > 0);
    $check->close();

    // ✅ 10. Insert student into database
    $stmtInsert = $conn->prepare("
        INSERT INTO students 
        (reg_no, first_name, last_name, email, phone, password, date_of_birth, gender, course_id, year_of_study)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
    ");

    $stmtInsert->bind_param(
        "ssssssssi",
        $reg_no,
        $first_name,
        $last_name,
        $email,
        $phone,
        $hashed_password,
        $date_of_birth,
        $gender,
        $course_id
    );

    $stmtInsert->execute();
    $stmtInsert->close();

    // ✅ 11. Success message & redirect to login
    $_SESSION['success'] = "Registration successful! Please login.";
    header("Location: login.php");
    exit();
}

$conn->close();
?>
