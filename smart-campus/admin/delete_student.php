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

// Check if student exists in `students` table
$stmt = $conn->prepare("SELECT id FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    $_SESSION['error'] = "Student not found.";
    header("Location: managestudents.php");
    exit();
}

// Delete student
$del = $conn->prepare("DELETE FROM students WHERE id = ?");
$del->bind_param("i", $student_id);
if ($del->execute()) {
    $_SESSION['success'] = "Student deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting student: " . $del->error;
}
$del->close();

header("Location: managestudents.php");
exit();
?>
