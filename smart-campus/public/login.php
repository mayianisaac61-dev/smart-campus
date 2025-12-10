<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/login.css">

<div class="container">
    <h2>Login</h2>

    <!-- Show error message if set -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo htmlspecialchars($_SESSION['error']); 
                unset($_SESSION['error']); 
            ?>
        </div>
    <?php endif; ?>

    <form action="process_login.php" method="POST" class="login-form">
        <div>
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-group">
            <label>Password:</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <span id="togglePassword" class="toggle-password">👁️</span>
        </div>
        <button type="submit" name="login">Login</button>
    </form>

    <p class="mt-3">
        Don’t have an account? <a href="register.php">Register here</a>
    </p>
</div>

<!-- Toggle password visibility -->
<script>
const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');

togglePassword.addEventListener('click', () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
});
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
