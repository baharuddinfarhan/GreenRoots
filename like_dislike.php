<?php
session_start();
require_once('admin/inc/config.php');

// Check if user is logged in
if(empty($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first to like or dislike a blog.'); window.location.href='login.php';</script>";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['blog_id'])) {
    $blog_id = (int)$_POST['blog_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Check if user has already interacted with this blog
        $stmt = $pdo->prepare("SELECT * FROM tbl_blog_likes WHERE blog_id = ? AND user_id = ?");
        $stmt->execute([$blog_id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Handle Like
        if(isset($_POST['like'])) {
            if($row) {
                if($row['liked']) {
                    // Undo Like
                    $pdo->prepare("UPDATE tbl_blog_likes SET liked = 0 WHERE id = ?")->execute([$row['id']]);
                    $pdo->prepare("UPDATE tbl_blogs SET likes = likes - 1 WHERE blog_id = ?")->execute([$blog_id]);
                } else {
                    // Like & remove dislike if any
                    $pdo->prepare("UPDATE tbl_blog_likes SET liked = 1, disliked = 0 WHERE id = ?")->execute([$row['id']]);
                    $pdo->prepare("UPDATE tbl_blogs SET likes = likes + 1, dislikes = dislikes - ? WHERE blog_id = ?")
                        ->execute([$row['disliked'], $blog_id]);
                }
            } else {
                // New Like
                $pdo->prepare("INSERT INTO tbl_blog_likes (blog_id, user_id, liked) VALUES (?, ?, 1)")->execute([$blog_id, $user_id]);
                $pdo->prepare("UPDATE tbl_blogs SET likes = likes + 1 WHERE blog_id = ?")->execute([$blog_id]);
            }
        }

        // Handle Dislike
        if(isset($_POST['dislike'])) {
            if($row) {
                if($row['disliked']) {
                    // Undo Dislike
                    $pdo->prepare("UPDATE tbl_blog_likes SET disliked = 0 WHERE id = ?")->execute([$row['id']]);
                    $pdo->prepare("UPDATE tbl_blogs SET dislikes = dislikes - 1 WHERE blog_id = ?")->execute([$blog_id]);
                } else {
                    // Dislike & remove like if any
                    $pdo->prepare("UPDATE tbl_blog_likes SET disliked = 1, liked = 0 WHERE id = ?")->execute([$row['id']]);
                    $pdo->prepare("UPDATE tbl_blogs SET dislikes = dislikes + 1, likes = likes - ? WHERE blog_id = ?")
                        ->execute([$row['liked'], $blog_id]);
                }
            } else {
                // New Dislike
                $pdo->prepare("INSERT INTO tbl_blog_likes (blog_id, user_id, disliked) VALUES (?, ?, 1)")->execute([$blog_id, $user_id]);
                $pdo->prepare("UPDATE tbl_blogs SET dislikes = dislikes + 1 WHERE blog_id = ?")->execute([$blog_id]);
            }
        }

    } catch(PDOException $e) {
        // Log or display error safely
        echo "Database error: " . $e->getMessage();
    }
}

// Redirect back to blogs page
header('Location: blogs.php');
exit;
