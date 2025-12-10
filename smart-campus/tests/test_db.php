<?php
// Database credentials
$host = "localhost";   // or 127.0.0.1
$user = "root";        // default in XAMPP
$pass = "";            // default is empty
$db   = "smart_campus";

// Try to connect
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Database connection successful!";
}

$conn->close();
?>
