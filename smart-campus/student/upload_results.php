<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/config.php"; // SITE_URL

// Auth check
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student info
$stmt = $conn->prepare("SELECT id, first_name, last_name, reg_no, result_slip FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Subjects & alias mapping
$subjects = [
    'english','kiswahili','mathematics','biology','physics','chemistry',
    'history_government','geography','cre','ire','hre','agriculture','business_studies',
    'home_science','computer_studies','art_design','music','french','german','arabic'
];

$subjectAliases = [
    'english'=>['english','eng'],
    'kiswahili'=>['kiswahili','swahili'],
    'mathematics'=>['mathematics','maths','math'],
    'biology'=>['biology','bio'],
    'physics'=>['physics','phy'],
    'chemistry'=>['chemistry','chem'],
    'history_government'=>['history','government','history & government'],
    'geography'=>['geography','geo'],
    'cre'=>['cre','religious education'],
    'ire'=>['ire','islamic religious education'],
    'hre'=>['hre','human religious education'],
    'agriculture'=>['agriculture','agri'],
    'business_studies'=>['business studies','bs'],
    'home_science'=>['home science','home_sciences'],
    'computer_studies'=>['computer studies','ict'],
    'art_design'=>['art & design','art'],
    'music'=>['music'],
    'french'=>['french'],
    'german'=>['german'],
    'arabic'=>['arabic']
];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['result_slip'])) {

    $uploadDir = __DIR__ . "/../uploads/results/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $file = $_FILES['result_slip'];
    $allowedTypes = ['application/pdf','image/jpeg','image/png'];

    if (in_array($file['type'], $allowedTypes)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = "result_" . $student['id'] . "_" . time() . "." . $ext;
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Update student table
            $stmt = $conn->prepare("UPDATE students SET result_slip=? WHERE id=?");
            $stmt->bind_param("si", $filename, $student['id']);
            $stmt->execute();
            $stmt->close();

            // ---- OCR with Tesseract ----
            $tessPath = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe"'; // Adjust path
            $outputFile = tempnam(sys_get_temp_dir(), 'tess');
            $cmd = $tessPath . ' ' . escapeshellarg($targetFile) . ' ' . escapeshellarg($outputFile) . ' -l eng';
            exec($cmd, $ocr_output, $return_var);

            if ($return_var !== 0) {
                $_SESSION['error'] = "OCR failed. Check Tesseract path or file.";
            } elseif (!file_exists($outputFile.'.txt')) {
                $_SESSION['error'] = "OCR did not produce output file.";
            } else {
                $text = strtolower(file_get_contents($outputFile.'.txt'));
                $grades = [];
                foreach ($subjects as $sub) {
                    $grades[$sub] = '-';
                    foreach ($subjectAliases[$sub] as $alias) {
                        if (preg_match('/'.preg_quote($alias).'[:\s-]*([a-f][\+\-]?)/i', $text, $match)) {
                            $grades[$sub] = strtoupper($match[1]);
                            break;
                        }
                    }
                }

                // Ensure student_results table has UNIQUE(student_id)
                $cols = implode(',', array_merge(['student_id'], $subjects));
                $placeholders = implode(',', array_fill(0, count($subjects)+1, '?'));
                $updateCols = [];
                foreach ($subjects as $s){ $updateCols[] = "$s=VALUES($s)"; }

                $sql = "INSERT INTO student_results ($cols) VALUES ($placeholders) 
                        ON DUPLICATE KEY UPDATE ".implode(',', $updateCols);
                $stmt = $conn->prepare($sql);

                $types = 'i' . str_repeat('s', count($subjects));
                $params = array_merge([$student_id], array_values($grades));
                $stmt->bind_param($types, ...$params);
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Result slip uploaded and processed successfully!";
                } else {
                    $_SESSION['error'] = "Failed to insert OCR results: " . $stmt->error;
                }
                $stmt->close();
            }

            $student['result_slip'] = $filename;
        } else {
            $_SESSION['error'] = "Failed to upload file.";
        }
    } else {
        $_SESSION['error'] = "Invalid file type! Only PDF, JPG, PNG allowed.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch student results
$stmt = $conn->prepare("SELECT * FROM student_results WHERE student_id=?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student_results = $result->fetch_assoc();
$stmt->close();

// Page CSS
$customCss = SITE_URL."/assets/css/upload_results.css";
?>

<?php include __DIR__ . "/../includes/header.php"; ?>
<link rel="stylesheet" href="<?php echo $customCss; ?>">

<div class="container digital-container">
    <h2>Upload Result Slip</h2>
    <p>Student: <strong><?php echo htmlspecialchars($student['first_name'].' '.$student['last_name']); ?></strong></p>
    <p>Reg No: <strong><?php echo $student['reg_no']; ?></strong></p>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if(empty($student['result_slip'])): ?>
        <form action="" method="POST" enctype="multipart/form-data" class="upload-form digital-form">
            <div class="form-group">
                <label for="result_slip">Choose Result Slip (PDF, JPG, PNG)</label>
                <input type="file" name="result_slip" id="result_slip" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload Result Slip</button>
        </form>
    <?php else: ?>
        <div class="alert alert-info mt-3">You have already uploaded your result slip.</div>
    <?php endif; ?>

    <?php if(!empty($student_results)): ?>
        <div class="mt-4">
            <h4>My Extracted Results</h4>
            <table class="table table-bordered table-striped results-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($subjects as $sub): ?>
                        <tr>
                            <td><?php echo ucwords(str_replace('_',' ',$sub)); ?></td>
                            <td><?php echo $student_results[$sub] ?? '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <form action="" method="get">
                <button type="submit" class="btn btn-warning">Reload Result Slip</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="../public/dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
