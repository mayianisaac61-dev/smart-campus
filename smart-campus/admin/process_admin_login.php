<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php";

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, first_name, last_name, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $admin = $result->fetch_assoc();

        if(password_verify($password, $admin['password'])){
            // Login success
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['first_name'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password!";
            header("Location: admin_login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Admin not found!";
        header("Location: admin_login.php");
        exit();
    }
}
?>
