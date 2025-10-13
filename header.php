<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");

$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

/* ========================
   FETCH BASIC SETTINGS
======================== */
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $logo = $row['logo'];
    $favicon = $row['favicon'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $meta_title_home = $row['meta_title_home'];
    $meta_keyword_home = $row['meta_keyword_home'];
    $meta_description_home = $row['meta_description_home'];
}

/* ========================
   FETCH ABOUT & CONTACT PAGE TITLES SAFELY
======================== */
$statement = $pdo->prepare("SELECT about_title, contact_title, about_meta_title, about_meta_keyword, about_meta_description, contact_meta_title, contact_meta_keyword, contact_meta_description FROM tbl_page WHERE id=1 LIMIT 1");
$statement->execute();
$row = $statement->fetch(PDO::FETCH_ASSOC);

$about_title = isset($row['about_title']) ? $row['about_title'] : 'About Us';
$contact_title = isset($row['contact_title']) ? $row['contact_title'] : 'Contact Us';
$about_meta_title = isset($row['about_meta_title']) ? $row['about_meta_title'] : 'About Us';
$about_meta_keyword = isset($row['about_meta_keyword']) ? $row['about_meta_keyword'] : '';
$about_meta_description = isset($row['about_meta_description']) ? $row['about_meta_description'] : '';
$contact_meta_title = isset($row['contact_meta_title']) ? $row['contact_meta_title'] : 'Contact Us';
$contact_meta_keyword = isset($row['contact_meta_keyword']) ? $row['contact_meta_keyword'] : '';
$contact_meta_description = isset($row['contact_meta_description']) ? $row['contact_meta_description'] : '';

$cur_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

<link rel="icon" type="image/png" href="assets/uploads/<?php echo $favicon; ?>">

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
/* ===================== MENU STYLE ===================== */
.menu-container {
  background-color: #4f806b;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0;
  margin: 0;
  border-radius: 50px;
  width: 100%;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  height: 60px;
}

.menu-wrapper {
  display: flex;
  justify-content: center;
  width: 100%;
}

.menu {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
}

.menu li {
  position: relative;
}

.menu li a {
  display: block;
  padding: 14px 24px;
  color: #ffffff;
  font-weight: 500;
  text-decoration: none;
  transition: all 0.3s ease;
  font-family: 'Poppins', sans-serif;
}

.menu li a:hover {
  background-color: #1b4332;
}

/* Active Page */
.menu li.active a {
  background-color: #1b4332;
}

/* Upward Triangle Indicator */
.menu li.active::after {
  content: "";
  position: absolute;
  bottom: -6px;
  left: 50%;
  transform: translateX(-50%);
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  border-top: 7px solid #1b4332;
}

/* Dropdown */
.menu li.has-dropdown {
  position: relative;
}

.menu li.has-dropdown ul {
  position: absolute;
  top: 100%;
  left: 0;
  background-color: #4f806b;
  min-width: 180px;
  z-index: 999;
  display: none;
  list-style: none;
  padding: 0;
  margin: 0;
  border-radius: 0 0 6px 6px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.menu li.has-dropdown ul li a {
  padding: 10px 20px;
  color: white;
  display: block;
  white-space: nowrap;
}

.menu li.has-dropdown ul li a:hover {
  background-color: #3e6659;
}

.menu li.has-dropdown:hover > ul {
  display: block;
}

.header .right ul li a.active-top {
    color: brown !important;
}

/* Responsive Fix */
@media (max-width: 768px) {
  .menu {
    flex-direction: column;
    align-items: center;
  }
  .menu li a {
    padding: 12px 20px;
  }
}
</style>

<?php
/* ========================
   PAGE META HANDLING
======================== */
if (in_array($cur_page, ['index.php','login.php','registration.php','cart.php','checkout.php','product-category.php','product.php'])) {
    echo "<title>{$meta_title_home}</title>
    <meta name='keywords' content='{$meta_keyword_home}'>
    <meta name='description' content='{$meta_description_home}'>";
}
elseif ($cur_page == 'about.php') {
    echo "<title>{$about_meta_title}</title>
    <meta name='keywords' content='{$about_meta_keyword}'>
    <meta name='description' content='{$about_meta_description}'>";
}
elseif ($cur_page == 'contact.php') {
    echo "<title>{$contact_meta_title}</title>
    <meta name='keywords' content='{$contact_meta_keyword}'>
    <meta name='description' content='{$contact_meta_description}'>";
}
elseif ($cur_page == 'dashboard.php') {
    echo "<title>Dashboard - {$meta_title_home}</title>";
}
elseif ($cur_page == 'customer-profile-update.php') {
    echo "<title>Update Profile - {$meta_title_home}</title>";
}
elseif ($cur_page == 'customer-billing-shipping-update.php') {
    echo "<title>Billing & Shipping - {$meta_title_home}</title>";
}
elseif ($cur_page == 'customer-password-update.php') {
    echo "<title>Update Password - {$meta_title_home}</title>";
}
elseif ($cur_page == 'customer-order.php') {
    echo "<title>Orders - {$meta_title_home}</title>";
}

/* OG tags for product.php */
if ($cur_page == 'product.php' && isset($_REQUEST['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $stmt->execute([$_REQUEST['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product) {
        $og_photo = $product['p_featured_photo'];
        $og_title = $product['p_name'];
        $og_slug = 'product.php?id='.$_REQUEST['id'];
        $og_description = substr(strip_tags($product['p_description']),0,200).'...';
        echo "
        <meta property='og:title' content='{$og_title}'>
        <meta property='og:type' content='website'>
        <meta property='og:url' content='".BASE_URL.$og_slug."'>
        <meta property='og:description' content='{$og_description}'>
        <meta property='og:image' content='assets/uploads/{$og_photo}'>";
    }
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

</head>
<body>

<!-- ======================== HEADER ======================== -->
<div class="header">
    <div class="container">
        <div class="row inner">
            <div class="col-md-4 logo">
                <a href="index.php">
                    <img src="assets/uploads/<?php echo $logo; ?>" alt="logo" style="width:200px;height:50px;">
                </a>
            </div>

            <div class="col-md-3 search-area">
                <form class="navbar-form navbar-left" role="search" action="search-result.php" method="get" style="width:400px;">
                    <div class="form-group">
                        <input type="text" class="form-control search-top" placeholder="Search for plants ..." name="search_text">
                    </div>
                    <button type="submit" class="btn btn-danger">Search</button>
                </form>
            </div>

            <div class="col-md-5 right">
                <ul>
                    <?php if(isset($_SESSION['customer'])): ?>
                        <li><i class="fa fa-user"></i> Logged in as <?php echo htmlspecialchars($_SESSION['customer']['cust_name']); ?></li>
                        <li><a href="dashboard.php" class="<?php if($cur_page == 'dashboard.php'){echo 'active-top';} ?>"><i class="fa fa-user"></i> My Profile</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fa fa-sign-in"></i> Login</a></li>
                        <li><a href="registration.php"><i class="fa fa-user-plus"></i> Register</a></li>
                    <?php endif; ?>

                    <li>
                        <a href="cart.php" class="<?php if($cur_page == 'cart.php'){echo 'active-top';} ?>">
                            <i class="fa fa-shopping-cart"></i> Cart (৳
                            <?php
                            if(isset($_SESSION['cart_p_id'])) {
                                $table_total_price = 0;
                                foreach($_SESSION['cart_p_qty'] as $i => $qty) {
                                    $price = $_SESSION['cart_p_current_price'][$i];
                                    $table_total_price += $qty * $price;
                                }
                                echo $table_total_price;
                            } else {
                                echo '0.00';
                            }
                            ?>)
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ======================== NAVIGATION MENU ======================== -->
<div class="nav" style="background:none;">
    <div class="container">
        <div class="row">
            <div class="col-md-12 pl_0 pr_0">
                <div class="menu-container">
                    <div class="menu-wrapper">
                        <ul class="menu">
                            <li class="<?php if($cur_page == 'index.php'){echo 'active';} ?>"><a href="index.php">Home</a></li>
                            <li class="has-dropdown <?php if($cur_page == 'product-category.php'){echo 'active';} ?>">
                                <a href="#">Plants Categories</a>
                                <ul>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT * FROM tbl_category WHERE show_on_menu=1");
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($categories as $cat): ?>
                                        <li><a href="product-category.php?id=<?php echo $cat['tcat_id']; ?>&type=top-category"><?php echo htmlspecialchars($cat['tcat_name']); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li class="<?php if($cur_page == 'about.php'){echo 'active';} ?>"><a href="about.php"><?php echo $about_title; ?></a></li>
                            <li class="<?php if($cur_page == 'contact.php'){echo 'active';} ?>"><a href="contact.php"><?php echo $contact_title; ?></a></li>
                            <li class="<?php if($cur_page == 'blogs.php'){echo 'active';} ?>"><a href="blogs.php">Blogs</a></li>
                            <li class="<?php if($cur_page == 'view_problems.php'){echo 'active';} ?>"><a href="view_problems.php">Expert Advice</a></li>
                            <li class="<?php if(strpos($cur_page, 'seller') !== false){echo 'active';} ?>"><a href="seller/index.php">Be a Seller</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ======================== END HEADER ======================== -->
