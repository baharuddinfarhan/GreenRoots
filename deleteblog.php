<?php
session_start();
require_once('admin/inc/config.php');


if(isset($_POST['blog_id']) && isset($_SESSION['customer'])) {
    $blog_id = $_POST['blog_id'];
    $user_id = $_SESSION['customer']['cust_id'];
    try {
        $statement = $pdo->prepare("DELETE FROM tbl_blogs WHERE blog_id = ? AND user_id = ?");
        $statement->execute([$blog_id, $user_id]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
header('Location: myblog.php');
exit;
