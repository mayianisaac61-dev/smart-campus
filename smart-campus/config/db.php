<?php
$host = "localhost";
$user = "root";  // default in XAMPP
$pass = "";      // default in XAMPP (empty password)
$dbname = "smart_campus";

// create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
