<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link notices.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/notices.css">

<div class="container">
    <h2>Student Notices</h2>

    <!-- Show error message if set -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']); 
            ?>
        </div>
    <?php endif; ?>

    <!-- Show success message if set -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
                echo htmlspecialchars($_SESSION['success']); 
                unset($_SESSION['success']); 
            ?>
        </div>
    <?php endif; ?>

    <div class="notices-list">
        <div class="notice-card important">
            <h3>📢 Semester Exams Schedule Released</h3>
            <p>The final semester exam timetable has been published. Please check the notice board or student portal for details.</p>
            <small>Posted on: 2025-09-01</small>
        </div>

        <div class="notice-card general">
            <h3>🎓 Graduation Registration Open</h3>
            <p>All final-year students are requested to register for graduation by 2025-09-20.</p>
            <small>Posted on: 2025-08-28</small>
        </div>

        <div class="notice-card event">
            <h3>⚽ Sports Day</h3>
            <p>The annual university sports day will be held on 2025-09-15 at the main stadium. All students are encouraged to participate.</p>
            <small>Posted on: 2025-08-25</small>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
