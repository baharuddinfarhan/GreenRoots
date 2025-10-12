<?php
require_once('header.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $advice_id = $_POST['advice_id'];
    $stmt = $pdo->prepare("UPDATE tbl_advices SET love_count = love_count + 1 WHERE advice_id = ?");
    $stmt->execute([$advice_id]);
}
header("Location: view_problems.php");
exit;
?>
