<?php
require_once('header.php'); // header.php already handles session_start()
?>

<style>
.result-box {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    max-width: 700px;
    margin: 40px auto;
    text-align: center;
}
.result-box h2 {
    color: #4f806b;
    margin-bottom: 20px;
}
.result-box p {
    font-size: 1.1em;
    color: #333; /* Default text color */
}
.result-box img {
    max-width: 300px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 2px solid #4f806b;
}
/* Color only for "Suggested Cure:" label */
.suggested-cure-label {
    color: #4f806b; /* Greenish theme color */
    font-weight: bold;
}
</style>

<div class="result-box">
<?php
// Check if an uploaded image exists in session
if (!isset($_SESSION['uploaded_image'])) {
    echo "<p style='color:red;'>No image found. Please upload again.</p>";
    exit;
}

$imagePath = $_SESSION['uploaded_image'];
unset($_SESSION['uploaded_image']); // Clear session to avoid reuse

// Convert Windows backslashes to forward slashes for Python
$imagePath = str_replace("\\", "/", $imagePath);

// Prepare and execute Python command safely
$command = escapeshellcmd("python predict_leaf.py " . escapeshellarg($imagePath));
$output = shell_exec($command);

// Process only the last line of output (disease::suggestion)
$lines = explode("\n", trim($output));
$lastLine = end($lines);

if ($lastLine && str_contains($lastLine, "::")) {
    list($disease, $suggestion) = explode("::", $lastLine);

    // Display uploaded leaf image first
    echo "<img src='uploads/" . basename($imagePath) . "' alt='Uploaded Leaf'>";

    // Display disease
    echo "<h2>Disease Detected: <b>$disease</b></h2>";

    // Display suggested cure with colored label
    echo "<p><span class='suggested-cure-label'>Suggested Cure:</span><br>$suggestion</p>";
} else {
    // Handle prediction failure
    echo "<p style='color:red;'>Prediction failed. Please check your setup.</p>";
    if ($output) {
        // Optional debug output from Python
        echo "<pre>$output</pre>";
    }
}
?>
</div>

<?php require_once('footer.php'); ?>
