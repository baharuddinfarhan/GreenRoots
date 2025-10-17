<?php require_once('header.php'); ?>

<?php
if(!isset($_SESSION['customer'])) {
    header("location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['customer']['cust_id'];

    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['image']['name'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpeg', 'jpg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_extensions)) {
            $image_new_name = uniqid('uploaded_', true) . '.' . $file_ext;
            $target_file_path = $uploadDir . $image_new_name;

            if (move_uploaded_file($file_tmp, $target_file_path)) {
                $_SESSION['uploaded_image'] = $target_file_path;
                header("Location: predict_result.php");  // ✅ keep this same
                exit;
            } else {
                $_SESSION['error_message'] = "Error uploading file.";
            }
        } else {
            $_SESSION['error_message'] = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
        }
    } else {
        $_SESSION['error_message'] = "Please select an image to upload.";
    }
}
?>

<style>
body {
    background-image: none;
    background-color: initial;
    margin: 0;
    padding: 0;
    display: block;
}
.main-content-area {
    background-image: url('https://images.unsplash.com/photo-1517400538804-0c5a27c7324d?q=80&w=1974&auto=format&fit=crop');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-color: #4f806b;
    background-blend-mode: overlay;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    max-width: 600px;
    width: 90%;
    margin: 40px auto;
    text-align: center;
}
.main-content-area h2 {
    color: white;
    font-size: 2em;
    margin-bottom: 25px;
}
.main-content-area input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: white;
}
.main-content-area button[type="submit"] {
    padding: 12px 25px;
    background-color: rgb(113, 187, 129);
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1.1em;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-top: 20px;
}
.main-content-area button[type="submit"]:hover {
    background-color: rgb(89, 134, 96);
}
</style>

<div class="main-content-area">
    <h2>Upload Leaf Image</h2>

    <?php
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color:red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    <form action="ai_advice.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required accept="image/jpeg,image/png,image/gif">
        <button type="submit">Submit</button>
    </form>
</div>

<?php require_once('footer.php'); ?>
