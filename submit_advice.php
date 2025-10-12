<?php
require_once('header.php');
if(!isset($_SESSION['customer'])) {
    header("location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $problem_id = $_POST['problem_id'];
    $advice = strip_tags($_POST['advice']);
    $user_id = $_SESSION['customer']['cust_id'];

    $stmt = $pdo->prepare("INSERT INTO tbl_advices (problem_id, user_id, advice) VALUES (?, ?, ?)");
    $stmt->execute([$problem_id, $user_id, $advice]);
}
header("Location: view_problems.php");
exit;
?>
