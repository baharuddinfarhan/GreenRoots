<?php
session_start();
require_once('header.php');  // or config.php if needed

// Check if user is logged in
if(empty($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first to give love.'); window.location.href='login.php';</script>";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['advice_id'])) {
    $advice_id = (int)$_POST['advice_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Check if the user has already loved this advice
        $stmt = $pdo->prepare("SELECT * FROM tbl_advice_love WHERE advice_id = ? AND user_id = ?");
        $stmt->execute([$advice_id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            // Undo love
            $pdo->prepare("DELETE FROM tbl_advice_love WHERE id = ?")->execute([$row['id']]);
            $pdo->prepare("UPDATE tbl_advices SET love_count = love_count - 1 WHERE advice_id = ?")->execute([$advice_id]);
        } else {
            // Add new love
            $pdo->prepare("INSERT INTO tbl_advice_love (advice_id, user_id) VALUES (?, ?)")->execute([$advice_id, $user_id]);
            $pdo->prepare("UPDATE tbl_advices SET love_count = love_count + 1 WHERE advice_id = ?")->execute([$advice_id]);
        }

    } catch(PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

// Redirect back to view page
header("Location: view_problems.php");
exit;
