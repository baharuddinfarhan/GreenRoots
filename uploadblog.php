<?php
// Ensure error reporting is on during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('header.php'); // This includes your original header structure

if (!isset($_SESSION['customer'])) {
    header("location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $user_id = $_SESSION['customer']['cust_id'];

    $uploadDir = __DIR__ . '/uploads/'; // Use absolute path for robustness

    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create recursively with 755 permissions
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $file_type = $_FILES['image']['type'];

        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpeg', 'jpg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_extensions)) {
            // Generate a unique filename to prevent overwrites and clean filenames
            $image_new_name = uniqid('blog_img_', true) . '.' . $file_ext;
            $target_file_path = $uploadDir . $image_new_name;

            if (move_uploaded_file($file_tmp, $target_file_path)) {
                try {
                    $statement = $pdo->prepare("INSERT INTO tbl_blogs (user_id, image, title, description) VALUES (?, ?, ?, ?)");
                    $statement->execute([$user_id, $image_new_name, $title, $description]);
                    $_SESSION['success_message'] = "Blog uploaded successfully!";
                    header('Location: myblog.php'); // Redirect to a page that shows user's blogs or all blogs
                    exit;
                } catch (PDOException $e) {
                    $_SESSION['error_message'] = "Database Error: " . $e->getMessage();
                    // Optional: Delete the uploaded file if DB insert fails
                    if (file_exists($target_file_path)) {
                        unlink($target_file_path);
                    }
                    header('Location: uploadblog.php'); // Redirect back to upload form with error
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Error uploading file: Could not move uploaded file. Check directory permissions.";
                header('Location: uploadblog.php');
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
            header('Location: uploadblog.php');
            exit;
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        // Handle specific upload errors (e.g., file too large, partial upload)
        $phpFileUploadErrors = array(
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        );
        $_SESSION['error_message'] = "File upload error: " . (isset($phpFileUploadErrors[$_FILES['image']['error']]) ? $phpFileUploadErrors[$_FILES['image']['error']] : 'Unknown error.');
        header('Location: uploadblog.php');
        exit;
    } else {
        $_SESSION['error_message'] = "Please select an image to upload.";
        header('Location: uploadblog.php');
        exit;
    }
}
?>

<style>
    /* Reset any body styles that might have been applied for the full background */
    body {
        background-image: none; /* Ensure no background image on body */
        background-color: initial; /* Revert to default background color */
        min-height: auto; /* Remove min-height */
        margin: 0;
        padding: 0;
        display: block; /* Revert to default display */
    }

   

    /* Styling for the main content area (where your form is located) */
    .main-content-area {
        /* This container now gets the background image */
        background-image: url('https://images.unsplash.com/photo-1517400538804-0c5a27c7324d?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D'); /* REPLACE THIS URL */
        background-size: cover; /* Cover the entire area of this container */
        background-position: center center; /* Center the image within this container */
        background-repeat: no-repeat;
        /* Overlay a semi-transparent white color on top of the image */
        background-color: #4f806b; /* Semi-transparent white background */
        background-blend-mode: overlay; /* Blend the background color with the image */

        padding: 30px;
        border-radius: 15px; /* Rounded corners for the content box */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
        max-width: 600px; /* Max width for the content box */
        width: 90%; /* Responsive width */
        margin: 40px auto; /* Top/bottom margin, auto for horizontal centering */
        
        display: flex; /* Flexbox for centering content within this area */
        flex-direction: column;
        align-items: center; /* Horizontally center content */
        justify-content: center; /* Vertically center content if there's extra space */
        text-align: left; /* Reset text alignment for form elements */
    }

    .main-content-area h2 {
        margin-top: 0; /* Remove default top margin from heading */
        color: #333;
        font-size: 2em;
        margin-bottom: 25px;
        color: white;
    }

    /* Adjust the form and its elements */
    .main-content-area form {
        width: 100%; /* Make form take full width of its parent container */
        margin: 0; /* Override any default form margins */
        display: flex;
        flex-direction: column;
        gap: 20px; /* Space between form elements */
    }

    .main-content-area label {
        font-weight: bold;
        color:white;

    }

    .main-content-area input[type="text"],
    .main-content-area textarea {
        width: calc(100% - 22px); /* Full width minus padding and border */
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box; /* Include padding and border in width */
        font-size: 1em;
    }

    .main-content-area input[type="file"] {
        padding: 5px 0;
        border: none; /* No border for file input */
    }

    .main-content-area textarea {
        resize: vertical; /* Allow vertical resizing */
    }

    .main-content-area button[type="submit"] {
        padding: 12px 25px;
        background-color:rgb(113, 187, 129); /* Green button */
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.1em;
        transition: background-color 0.3s ease;
        width: 100%; /* Make button full width */
        box-sizing: border-box;
    }

    .main-content-area button[type="submit"]:hover {
        background-color:rgb(89, 134, 96); /* Darker green on hover */
    }

    /* Message styling */
    .main-content-area p {
        width: 100%;
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
    }
    .main-content-area p[style*="color:green"] {
        color: green !important; /* Ensure visibility */
    }
    .main-content-area p[style*="color:red"] {
        color: red !important; /* Ensure visibility */
    }
</style>

<div class="main-content-area">
    <h2 style="text-align:center;">Upload Blog</h2>

    <?php
    // Display success or error messages
    if (isset($_SESSION['success_message'])) {
        echo '<p style="color:green; text-align:center;">' . $_SESSION['success_message'] . '</p>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color:red; text-align:center;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    <form action="uploadblog.php" method="POST" enctype="multipart/form-data">
        <label for="image_upload">Upload Image:</label>
        <input type="file" name="image" id="image_upload" required accept="image/jpeg,image/png,image/gif">

        <label for="blog_title">Title:</label>
        <input type="text" name="title" id="blog_title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">

        <label for="blog_description">Description:</label>
        <textarea name="description" id="blog_description" rows="8" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>

        <button type="submit">Upload</button>
    </form>
</div>

<?php require_once('footer.php'); ?>