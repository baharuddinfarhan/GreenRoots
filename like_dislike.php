<?php
session_start();
require_once('admin/inc/config.php');


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['blog_id'])) {
    $blog_id = $_POST['blog_id'];
    try {
        if(isset($_POST['like'])) {
            $statement = $pdo->prepare("UPDATE tbl_blogs SET likes = likes + 1 WHERE blog_id = ?");
        } else {
            $statement = $pdo->prepare("UPDATE tbl_blogs SET dislikes = dislikes + 1 WHERE blog_id = ?");
        }
        $statement->execute([$blog_id]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
header('Location: blogs.php');
exit;
