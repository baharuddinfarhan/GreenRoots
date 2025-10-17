<?php require_once('header.php'); ?>

<?php
if(!isset($_SESSION['customer'])) {
    header("location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
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
            $image_new_name = uniqid('problem_img_', true) . '.' . $file_ext;
            $target_file_path = $uploadDir . $image_new_name;

            if (move_uploaded_file($file_tmp, $target_file_path)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO tbl_problems (user_id, image, title, description) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$user_id, $image_new_name, $title, $description]);
                    $_SESSION['success_message'] = "Problem uploaded successfully!";
                    header("Location: my_problems.php");
                    exit;
                } catch (PDOException $e) {
                    $_SESSION['error_message'] = "Database Error: " . $e->getMessage();
                    if (file_exists($target_file_path)) unlink($target_file_path);
                    header("Location: upload_problem.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Error uploading file. Check directory permissions.";
                header("Location: upload_problem.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
            header("Location: upload_problem.php");
            exit;
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] != UPLOAD_ERR_NO_FILE) {
        $_SESSION['error_message'] = "File upload error.";
        header("Location: upload_problem.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Please select an image to upload.";
        header("Location: upload_problem.php");
        exit;
    }
}
?>

<style>
body {
    background-image: none;
    background-color: initial;
    min-height: auto;
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
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: left;
}
.main-content-area h2 {
    margin-top: 0;
    color: white;
    font-size: 2em;
    margin-bottom: 25px;
}
.main-content-area form {
    width: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.main-content-area label {
    font-weight: bold;
    color: white;
}
.main-content-area input[type="text"],
.main-content-area textarea {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1em;
}
.main-content-area input[type="file"] {
    padding: 5px 0;
    border: none;
}
.main-content-area textarea {
    resize: vertical;
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
    box-sizing: border-box;
}
.main-content-area button[type="submit"]:hover {
    background-color: rgb(89, 134, 96);
}
.main-content-area p {
    width: 100%;
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}
</style>

<div class="main-content-area">
    <h2>Write Your Problem</h2>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<p style="color:green;">' . $_SESSION['success_message'] . '</p>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<p style="color:red;">' . $_SESSION['error_message'] . '</p>';
        unset($_SESSION['error_message']);
    }
    ?>

    <form action="upload_problem.php" method="POST" enctype="multipart/form-data">
        <label>Upload Image:</label>
        <input type="file" name="image" required accept="image/jpeg,image/png,image/gif">

        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="8" required></textarea>

        <button type="submit">Submit Problem</button>
    </form>
</div>

<?php require_once('footer.php'); ?>
