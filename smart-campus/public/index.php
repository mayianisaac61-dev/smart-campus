<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Optional page-specific CSS
$customCss = SITE_URL . "/assets/css/index.css";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="text-center mt-5">
    <h1>Welcome to Smart Campus</h1>
    <p class="lead">Your complete campus management system</p>

    <?php if(isset($_SESSION['user_id'])): ?>
        <p>Hello, <?= htmlspecialchars($_SESSION['username'] ?? 'Student'); ?> 👋</p>
        <a href="<?php echo SITE_URL; ?>/public/dashboard.php" class="btn btn-primary m-2">Go to Dashboard</a>
        <a href="<?php echo SITE_URL; ?>/public/logout.php" class="btn btn-danger m-2">Logout</a>
    <?php else: ?>
        <a href="<?php echo SITE_URL; ?>/public/login.php" class="btn btn-success m-2">Login</a>
        <a href="<?php echo SITE_URL; ?>/public/register.php" class="btn btn-primary m-2">Register</a>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
