<?php
// Path to footer.css
$footerCss = SITE_URL . "/assets/css/footer.css";
?>

<!-- Footer CSS -->
<link rel="stylesheet" href="<?php echo $footerCss; ?>">

</main> <!-- Close main content from header.php -->

<footer class="mt-auto bg-light py-3 shadow-sm">
    <div class="container text-center">
        <p>&copy; <?php echo date("Y"); ?> Smart Campus Management System. All Rights Reserved.</p>
    </div>
</footer>

<!-- Optional global JS -->
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</div> <!-- Close wrapper -->
</body>
</html>
