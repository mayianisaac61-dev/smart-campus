<?php
session_start(); // <-- Start session to use $_SESSION
require_once __DIR__ . "/../config/db.php";

if(!isset($_GET['id'])) {
    header("Location: managestudents.php");
    exit();
}

$student_id = intval($_GET['id']);

// Fetch student info
$stmt = $conn->prepare("SELECT result_uploaded, verified FROM students WHERE id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if(!$student) {
    $_SESSION['error'] = "Student not found.";
} elseif($student['result_uploaded'] == 0) {
    $_SESSION['error'] = "Cannot verify student: result slip not uploaded.";
} elseif($student['verified'] == 1) {
    $_SESSION['error'] = "Student already verified.";
} else {
    $update = $conn->prepare("UPDATE students SET verified=1 WHERE id=?");
    $update->bind_param("i", $student_id);
    if($update->execute()) {
        $_SESSION['success'] = "Student verified successfully!";
    } else {
        $_SESSION['error'] = "Failed to verify student: " . $update->error;
    }
    $update->close();
}

header("Location: managestudents.php");
exit();
