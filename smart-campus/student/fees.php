<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link fees.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/fees.css">

<div class="container">
    <h2>My Fees</h2>

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

    <table class="fees-table">
        <thead>
            <tr>
                <th>Semester</th>
                <th>Fee Amount</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Semester 1</td>
                <td>KSh 50,000</td>
                <td>KSh 50,000</td>
                <td>KSh 0</td>
                <td class="status-paid">Paid</td>
                <td><a href="#">Download</a></td>
            </tr>
            <tr>
                <td>Semester 2</td>
                <td>KSh 55,000</td>
                <td>KSh 30,000</td>
                <td>KSh 25,000</td>
                <td class="status-partial">Partial</td>
                <td><a href="#">Download</a></td>
            </tr>
            <tr>
                <td>Semester 3</td>
                <td>KSh 60,000</td>
                <td>KSh 0</td>
                <td>KSh 60,000</td>
                <td class="status-unpaid">Unpaid</td>
                <td><a href="#">N/A</a></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
