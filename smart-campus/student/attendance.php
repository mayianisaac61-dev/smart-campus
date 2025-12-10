<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link attendance.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/attendance.css">

<div class="container">
    <h2>My Attendance</h2>

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

    <table class="attendance-table">
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Total Classes</th>
                <th>Attended</th>
                <th>Attendance %</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>CS101</td>
                <td>Introduction to Programming</td>
                <td>40</td>
                <td>36</td>
                <td>90%</td>
                <td class="status-present">Good</td>
            </tr>
            <tr>
                <td>MA201</td>
                <td>Discrete Mathematics</td>
                <td>30</td>
                <td>20</td>
                <td>67%</td>
                <td class="status-warning">Low</td>
            </tr>
            <tr>
                <td>PH301</td>
                <td>Physics II</td>
                <td>25</td>
                <td>15</td>
                <td>60%</td>
                <td class="status-poor">Critical</td>
            </tr>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
