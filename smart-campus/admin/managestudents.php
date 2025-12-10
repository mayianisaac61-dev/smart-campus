<?php
session_start();
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../config/db.php";

// Fetch all students from `students` table
$query = "SELECT id, first_name, last_name, email, phone, created_at, is_verified 
          FROM students 
          ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<?php include __DIR__ . "/../includes/header.php"; ?>

<!-- Link students.css explicitly -->
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/students.css">

<div class="container">
    <h2>Manage Students</h2>

    <!-- Show success -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Show error -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="student-actions">
        <a href="add_student.php" class="btn btn-primary">+ Add New Student</a>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Registered</th>
                <th>Verified</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($student = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $student['id']; ?></td>
                        <td><?= htmlspecialchars($student['first_name'] . " " . $student['last_name']); ?></td>
                        <td><?= htmlspecialchars($student['email']); ?></td>
                        <td><?= htmlspecialchars($student['phone']); ?></td>
                        <td><?= date("d M, Y", strtotime($student['created_at'])); ?></td>
                        <td>
                            <?php if($student['is_verified']): ?>
                                <span class="badge verified">Verified ✅</span>
                            <?php else: ?>
                                <span class="badge not-verified">Pending ⏳</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_student.php?id=<?= $student['id']; ?>" class="btn btn-secondary">Edit</a>
                            <a href="delete_student.php?id=<?= $student['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            <?php if(!$student['is_verified']): ?>
                                <a href="verify_student.php?id=<?= $student['id']; ?>" class="btn btn-success">Verify</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
