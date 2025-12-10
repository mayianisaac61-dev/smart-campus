<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link library.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/library.css">

<div class="container">
    <h2>My Library</h2>

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

    <table class="library-table">
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>Issued Date</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Fine</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Clean Code</td>
                <td>Robert C. Martin</td>
                <td>2025-08-01</td>
                <td>2025-08-21</td>
                <td class="status-returned">Returned</td>
                <td>KSh 0</td>
            </tr>
            <tr>
                <td>Introduction to Algorithms</td>
                <td>Cormen et al.</td>
                <td>2025-08-05</td>
                <td>2025-08-25</td>
                <td class="status-issued">Issued</td>
                <td>KSh 0</td>
            </tr>
            <tr>
                <td>Artificial Intelligence: A Modern Approach</td>
                <td>Russell & Norvig</td>
                <td>2025-07-10</td>
                <td>2025-07-30</td>
                <td class="status-overdue">Overdue</td>
                <td>KSh 500</td>
            </tr>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
