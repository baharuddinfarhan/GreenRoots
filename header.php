<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
    $logo = $row['logo'];
    $favicon = $row['favicon'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $meta_title_home = $row['meta_title_home'];
    $meta_keyword_home = $row['meta_keyword_home'];
    $meta_description_home = $row['meta_description_home'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

    <link rel="icon" type="image/png" href="assets/uploads/favicon.png?php echo $favicon; ?>">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="assets/css/jquery.bxslider.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/rating.css">
    <link rel="stylesheet" href="assets/css/spacing.css">
    <link rel="stylesheet" href="assets/css/bootstrap-touch-slider.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/tree-menu.css">
    <link rel="stylesheet" href="assets/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/responsive.css">

    <style>
        /* Parent list item for dropdowns */
        .menu ul li.has-dropdown {
            position: relative; /* Essential for positioning the dropdown */
        }

        /* The actual dropdown menu (nested ul) */
        .menu ul li.has-dropdown ul {
            position: absolute;
            top: 100%; /* Position below the parent link */
            left: 0;
            background-color: #4f806b; /* Match your menu's background color */
            min-width: 180px; /* Adjust as needed */
            z-index: 999; /* Ensure it appears above other content */
            display: none; /* Hidden by default */
            list-style: none; /* Remove bullet points */
            padding: 0;
            margin: 0;
            border-radius: 0 0 5px 5px; /* Rounded bottom corners */
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); /* Subtle shadow */
        }

        /* Dropdown list items */
        .menu ul li.has-dropdown ul li {
            float: none; /* Ensure sub-menu items stack vertically */
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Separator line */
        }

        .menu ul li.has-dropdown ul li:last-child {
            border-bottom: none; /* No separator for the last item */
        }

        /* Dropdown links */
        .menu ul li.has-dropdown ul li a {
            padding: 10px 20px; /* Padding for dropdown links */
            display: block;
            white-space: nowrap; /* Prevent text wrapping */
            color: white; /* Text color for dropdown links */
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .menu ul li.has-dropdown ul li a:hover {
            background-color: #3e6659; /* Darker background on hover */
        }

        /* Show dropdown on hover of the parent list item */
        .menu ul li.has-dropdown:hover > ul {
            display: block;
        }
    </style>

    <?php

    $statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
    foreach ($result as $row) {
        $about_meta_title = $row['about_meta_title'];
        $about_meta_keyword = $row['about_meta_keyword'];
        $about_meta_description = $row['about_meta_description'];
        $contact_meta_title = $row['contact_meta_title'];
        $contact_meta_keyword = $row['contact_meta_keyword'];
        $contact_meta_description = $row['contact_meta_description'];
    }

    $cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    
    if($cur_page == 'index.php' || $cur_page == 'login.php' || $cur_page == 'registration.php' || $cur_page == 'cart.php' || $cur_page == 'checkout.php' || $cur_page == 'product-category.php' || $cur_page == 'product.php') {
        ?>
        <title><?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }

    if($cur_page == 'about.php') {
        ?>
        <title><?php echo $about_meta_title; ?></title>
        <meta name="keywords" content="<?php echo $about_meta_keyword; ?>">
        <meta name="description" content="<?php echo $about_meta_description; ?>">
        <?php
    }
    
    if($cur_page == 'contact.php') {
        ?>
        <title><?php echo $contact_meta_title; ?></title>
        <meta name="keywords" content="<?php echo $contact_meta_keyword; ?>">
        <meta name="description" content="<?php echo $contact_meta_description; ?>">
        <?php
    }
    if($cur_page == 'product.php')
    {
        $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
        $statement->execute(array($_REQUEST['id']));
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                                
        foreach ($result as $row) 
        {
            $og_photo = $row['p_featured_photo'];
            $og_title = $row['p_name'];
            $og_slug = 'product.php?id='.$_REQUEST['id'];
            $og_description = substr(strip_tags($row['p_description']),0,200).'...';
        }
    }

    if($cur_page == 'dashboard.php') {
        ?>
        <title>Dashboard - <?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }
    if($cur_page == 'customer-profile-update.php') {
        ?>
        <title>Update Profile - <?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }
    if($cur_page == 'customer-billing-shipping-update.php') {
        ?>
        <title>Update Billing and Shipping Info - <?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }
    if($cur_page == 'customer-password-update.php') {
        ?>
        <title>Update Password - <?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }
    if($cur_page == 'customer-order.php') {
        ?>
        <title>Orders - <?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }
    ?>
    

    <?php if($cur_page == 'product.php'): ?>
        <meta property="og:title" content="<?php echo $og_title; ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo BASE_URL.$og_slug; ?>">
        <meta property="og:description" content="<?php echo $og_description; ?>">
        <meta property="og:image" content="assets/uploads/<?php echo $og_photo; ?>">
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>


</head>
<body>

<div class="header">
    <div class="container">
        <div class="row inner">
            <div class="col-md-4 logo">
                <a href="index.php"><img src="assets/uploads/<?php echo $logo; ?>" alt="logo image" style="width:200px; height:50px"></a>
            </div>

            <div class="col-md-3 search-area">
                <form class="navbar-form navbar-left" role="search" action="search-result.php" method="get" style="width:400px;">
                    <div class="form-group">
                        <input type="text" class="form-control search-top" placeholder="<?php echo "Search for plants ..."; ?>" name="search_text">
                    </div>
                    <button type="submit" class="btn btn-danger"><?php echo "Search"; ?></button>
                </form>
            </div>
            
            <div class="col-md-5 right">
                <ul>
                    
                    <?php
                    if(isset($_SESSION['customer'])) {
                        ?>
                        <li><i class="fa fa-user"></i> <?php echo "Logged in as"; ?> <?php echo $_SESSION['customer']['cust_name']; ?></li>
                        <li><a href="dashboard.php"><i class="fa fa-user"></i> <?php echo "My Profile"; ?></a></li>
                        <?php
                    } else {
                        ?>
                        <li><a href="login.php"><i class="fa fa-sign-in"></i> <?php echo "Login"; ?></a></li>
                        <li><a href="registration.php"><i class="fa fa-user-plus"></i> <?php echo "Register"; ?></a></li>
                        <?php   
                    }
                    ?>

                    <li><a href="cart.php"><i class="fa fa-shopping-cart"></i> <?php echo "Cart"; ?> (<?php echo "৳"; ?><?php
                    if(isset($_SESSION['cart_p_id'])) {
                        $table_total_price = 0;
                        $i=0;
                        foreach($_SESSION['cart_p_qty'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_qty[$i] = $value;
                        }                       $i=0;
                        foreach($_SESSION['cart_p_current_price'] as $key => $value) 
                        {
                            $i++;
                            $arr_cart_p_current_price[$i] = $value;
                        }
                        for($i=1;$i<=count($arr_cart_p_qty);$i++) {
                            $row_total_price = $arr_cart_p_current_price[$i]*$arr_cart_p_qty[$i];
                            $table_total_price = $table_total_price + $row_total_price;
                        }
                        echo $table_total_price;
                    } else {
                        echo '0.00';
                    }
                    ?>)</a></li>
                </ul>
            </div>
            
        </div>
    </div>
</div>

<div class="nav" style="background: none;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 pl_0 pr_0">
                <div class="menu-container" style="height:80px; width:max-content; color:#4f806b; border-radius:70px; background-color:#4f806b;">
                    <div class="menu">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            
                            <li class="has-dropdown">
                                <a href="#">Plants Categories</a> 
								<ul> 
									<?php
                            // Existing category loop (from tbl_category)
                            $statement = $pdo->prepare("SELECT * FROM tbl_category WHERE show_on_menu=1");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                ?>
                                <li><a href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category"><?php echo $row['tcat_name']; ?></a></li>
                                <?php
                            }
                            ?>

                            <?php
                            $statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);    
                            foreach ($result as $row) {
                                $about_title = $row['about_title'];
                                $contact_title = $row['contact_title'];
                            }
                            ?>
                                </ul>
                            </li>
                            

                            <li><a href="about.php"><?php echo $about_title; ?></a></li>
                            <li><a href="contact.php"><?php echo $contact_title; ?></a></li>
                            <li><a href="blogs.php">Blogs</a></li>
							<li><a href="view_problems.php">Expert Advice</a></li>
                            <li><a href="seller\index.php">Be a Seller</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>