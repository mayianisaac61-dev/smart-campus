<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include(__DIR__ . '/../config/db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['login'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['error'] = "Email and password are required!";
        header("Location: login.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // LOGIN SUCCESS → redirect to dashboard
            $_SESSION['student_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with that email!";
        header("Location: login.php");
        exit();
    }
}

// Block direct access
$_SESSION['error'] = "Invalid request!";
header("Location: login.php");
exit();
