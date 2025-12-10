<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link managestaff.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/staff.css">

<div class="container">
    <h2>Manage Staff</h2>

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

    <div class="staff-actions">
        <a href="add-staff.php" class="btn">Add New Staff</a>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Date Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Sample data (replace with DB loop) -->
            <tr>
                <td>1</td>
                <td>Mary</td>
                <td>Wanjiku</td>
                <td>mary@example.com</td>
                <td>+254 700 123 456</td>
                <td>Lecturer</td>
                <td>2025-01-10</td>
                <td>
                    <a href="edit-staff.php?id=1" class="btn">Edit</a>
                    <a href="delete-staff.php?id=1" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <tr>
                <td>2</td>
                <td>James</td>
                <td>Otieno</td>
                <td>james@example.com</td>
                <td>+254 711 654 321</td>
                <td>Administrator</td>
                <td>2024-11-20</td>
                <td>
                    <a href="edit-staff.php?id=2" class="btn">Edit</a>
                    <a href="delete-staff.php?id=2" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
