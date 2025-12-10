<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link profile.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/profile.css">

<div class="container">
    <h2>My Profile</h2>

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

    <?php
        // Try to pull user info from session (adjust keys if your session uses different names)
        $user = isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : [];

        $first_name = htmlspecialchars($user['first_name'] ?? $user['firstName'] ?? 'John');
        $last_name  = htmlspecialchars($user['last_name']  ?? $user['lastName']  ?? 'Doe');
        $email      = htmlspecialchars($user['email']      ?? 'john.doe@example.com');
        $phone      = htmlspecialchars($user['phone']      ?? '+254 712 345 678');
        $role       = htmlspecialchars($user['role']       ?? 'Student');
    ?>

    <div class="profile-section">
        <div class="profile-info">
            <p><strong>First Name:</strong> <?php echo $first_name; ?></p>
            <p><strong>Last Name:</strong> <?php echo $last_name; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>Phone:</strong> <?php echo $phone; ?></p>
            <p><strong>Role:</strong> <?php echo $role; ?></p>
        </div>

        <div class="profile-actions">
            <a href="edit_profile.php" class="btn">Edit Profile</a>
            <a href="change_password.php" class="btn">Change Password</a>
        </div>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
