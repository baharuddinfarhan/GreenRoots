<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");

$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

/* ========================
   FETCH BASIC SETTINGS FOR FAVICON
======================== */
$statement = $pdo->prepare("SELECT favicon FROM tbl_settings WHERE id=1");
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
$favicon = $row ? $row['favicon'] : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Seller Profile</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<!-- ✅ Favicon -->
	<link rel="icon" type="image/png" href="../assets/uploads/<?php echo htmlspecialchars($favicon); ?>">


	<!-- CSS Files -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/ionicons.min.css">
	<link rel="stylesheet" href="css/datepicker3.css">
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.css">
	<link rel="stylesheet" href="css/jquery.fancybox.css">
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<link rel="stylesheet" href="css/_all-skins.min.css">
	<link rel="stylesheet" href="css/on-off-switch.css"/>
	<link rel="stylesheet" href="css/summernote.css">
	<link rel="stylesheet" href="style.css">
</head>

<body class="hold-transition fixed skin-blue sidebar-mini" style="background-color: #4f806b;">

	<div class="wrapper">

		<!-- Header -->
		<header class="main-header">
			<a href="index.php" class="logo" style="background-color: #4f806b;">
				<span class="logo-lg">Green Roots</span>
			</a>

			<nav class="navbar navbar-static-top" style="background-color: #4f806b;">
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>

				<span style="float:left; line-height:50px; color:#fff; padding-left:15px; font-size:18px;">Seller Profile</span>

				<!-- Top Bar User Info / Switch to Buyer -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="../index.php">Switch to Buyer</a>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<?php $cur_page = substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1); ?>

		<!-- Sidebar -->
		<aside class="main-sidebar" style="background-color:#4f806b;">
			<section class="sidebar">
				<ul class="sidebar-menu">

					<li class="treeview <?php if($cur_page == 'index.php') {echo 'active';} ?>">
						<a href="index.php">
							<i class="fa fa-dashboard"></i> <span>Seller Dashboard</span>
						</a>
					</li>

					<li class="treeview <?php if(($cur_page == 'product.php') || ($cur_page == 'product-add.php') || ($cur_page == 'product-edit.php')) {echo 'active';} ?>">
						<a href="product.php">
							<i class="fa fa-shopping-bag"></i> <span>Product Management</span>
						</a>
					</li>

					<li class="treeview <?php if($cur_page == 'order.php') {echo 'active';} ?>">
						<a href="order.php">
							<i class="fa fa-sticky-note"></i> <span>Order Management</span>
						</a>
					</li>

					<li class="treeview <?php if($cur_page == 'feedback.php') {echo 'active';} ?>">
						<a href="feedback.php">
							<i class="fa fa-comments"></i> <span>View Feedbacks</span>
						</a>
					</li>

				</ul>
			</section>
		</aside>

		<!-- Content Wrapper -->
		<div class="content-wrapper">
