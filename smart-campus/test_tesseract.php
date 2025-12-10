<?php
$output = null;
$return_var = null;

$cmd = '"C:\\Program Files\\Tesseract-OCR\\tesseract.exe" --version';
exec($cmd, $output, $return_var);

echo "<h3>Output from Tesseract:</h3>";
echo "<pre>";
print_r($output);
echo "</pre>";

echo "<h3>Return code:</h3>";
echo $return_var;
