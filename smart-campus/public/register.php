<?php
session_start();
require_once __DIR__ . "/../config/config.php";
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link register.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/register.css">

<div class="container">
    <h2>Student Registration</h2>

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

    <form action="process_register.php" method="POST" class="register-form">
        <div>
            <label>First Name:</label>
            <input type="text" name="first_name" placeholder="Enter your first name" required>
        </div>
        <div>
            <label>Last Name:</label>
            <input type="text" name="last_name" placeholder="Enter your last name" required>
        </div>
        <div>
            <label>Email (Gmail only):</label>
            <input type="email" name="email" placeholder="Enter your Gmail" pattern=".+@gmail\.com" required>
        </div>
        <div>
            <label>Phone Number:</label>
            <input type="tel" name="phone" placeholder="Enter your phone number" pattern="[0-9]{10}" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter a strong password" 
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}" 
                title="Must contain at least 8 characters, one uppercase, one lowercase, one number, and one special character" 
                required>
        </div>
        <div>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
        </div>
        <!-- Optional fields if your DB supports them -->
        <div>
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth">
        </div>
        <div>
            <label>Gender:</label>
            <select name="gender">
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <button type="submit" name="register">Register</button>
    </form>

    <p class="mt-3">
        Already registered? <a href="login.php">Login here</a>
    </p>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
