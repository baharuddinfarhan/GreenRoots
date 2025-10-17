<?php require_once('header.php'); ?>

<?php if(isset($_SESSION['customer'])): ?>

<?php
// Fetch page info
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$page = $statement->fetch(PDO::FETCH_ASSOC);
$contact_title = $page['contact_title'];
$contact_banner = $page['contact_banner'];

// Fetch contact settings
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$settings = $statement->fetch(PDO::FETCH_ASSOC);

$contact_email = $settings['contact_email'];
$contact_phone = $settings['contact_phone'];
$contact_address = $settings['contact_address'];
?>

<div class="page-banner" style="background-image: url(assets/uploads/<?php echo $contact_banner; ?>);">
    <div class="inner">
        <h1><?php echo htmlspecialchars($contact_title); ?></h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">            
            <div class="col-md-12">
                <h3>Contact Form</h3>
                <div class="row cform">
                    <div class="col-md-8">
                        <div class="well well-sm">

<?php
// Server-side validation
$errors = [];
$success_message = '';

if(isset($_POST['form_contact']))
{
    $visitor_name = trim($_POST['visitor_name']);
    $visitor_email = trim($_POST['visitor_email']);
    $visitor_phone = trim($_POST['visitor_phone']);
    $visitor_message = trim($_POST['visitor_message']);

    // Name validation
    if(empty($visitor_name)) {
        $errors['visitor_name'] = 'Please enter your name.';
    } elseif(!preg_match("/^[a-zA-Z ]+$/", $visitor_name)) {
        $errors['visitor_name'] = 'Name can contain only letters and spaces.';
    }

    // Phone validation
    if(empty($visitor_phone)) {
        $errors['visitor_phone'] = 'Please enter your phone number.';
    } elseif(!preg_match("/^\+?[0-9]{1,17}$/", $visitor_phone)) {
        $errors['visitor_phone'] = 'Phone can contain only digits and optional "+" at start, max 17 digits.';
    }

    // Email validation
    if(empty($visitor_email)) {
        $errors['visitor_email'] = 'Please enter your email address.';
    } elseif(!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
        $errors['visitor_email'] = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $stmt->execute([$visitor_email]);
        if($stmt->rowCount() == 0){
            $errors['visitor_email'] = 'Email not registered. Please use a valid email.';
        }
    }

    // Message validation
    if(empty($visitor_message)) {
        $errors['visitor_message'] = 'Please enter your message.';
    }

    // Save if no errors
    if(empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO tbl_feedback (
                                    visitor_name,
                                    visitor_phone,
                                    visitor_email,
                                    visitor_message
                                ) VALUES (?,?,?,?)");
        $stmt->execute([
            htmlspecialchars($visitor_name),
            htmlspecialchars($visitor_phone),
            htmlspecialchars($visitor_email),
            htmlspecialchars($visitor_message)
        ]);

        $success_message = "Your feedback is sent successfully.";
        // Clear form
        $visitor_name = $visitor_email = $visitor_phone = $visitor_message = '';
    }
}
?>

<?php if(!empty($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<form action="" method="post" name="contact_form" novalidate>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control <?php echo isset($errors['visitor_name']) ? 'is-invalid' : ''; ?>" name="visitor_name" placeholder="Enter name" value="<?php echo isset($visitor_name) ? htmlspecialchars($visitor_name) : ''; ?>">
                <div class="invalid-feedback" style="color:red;"><?php echo $errors['visitor_name'] ?? ''; ?></div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control <?php echo isset($errors['visitor_email']) ? 'is-invalid' : ''; ?>" name="visitor_email" placeholder="Enter email address" value="<?php echo isset($visitor_email) ? htmlspecialchars($visitor_email) : ''; ?>">
                <div class="invalid-feedback" style="color:red;"><?php echo $errors['visitor_email'] ?? ''; ?></div>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" class="form-control <?php echo isset($errors['visitor_phone']) ? 'is-invalid' : ''; ?>" name="visitor_phone" placeholder="Enter phone number" value="<?php echo isset($visitor_phone) ? htmlspecialchars($visitor_phone) : ''; ?>">
                <div class="invalid-feedback" style="color:red;"><?php echo $errors['visitor_phone'] ?? ''; ?></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="visitor_message" class="form-control <?php echo isset($errors['visitor_message']) ? 'is-invalid' : ''; ?>" rows="9" cols="25" placeholder="Enter message"><?php echo isset($visitor_message) ? htmlspecialchars($visitor_message) : ''; ?></textarea>
                <div class="invalid-feedback" style="color:red;"><?php echo $errors['visitor_message'] ?? ''; ?></div>
            </div>
        </div>
        <div class="col-md-12">
            <input type="submit" value="Send Message" class="btn btn-primary pull-right" name="form_contact">
        </div>
    </div>
</form>

</div>
</div>

<div class="col-md-4">
    <legend><span class="glyphicon glyphicon-globe"></span> Get in Touch</legend>
    <address>
        <?php echo nl2br(htmlspecialchars($contact_address)); ?>
    </address>
    <address>
        <strong>Phone:</strong><br>
        <span><?php echo htmlspecialchars($contact_phone); ?></span>
    </address>
    <address>
        <strong>Email:</strong><br>
        <a href="mailto:<?php echo htmlspecialchars($contact_email); ?>">
            <span><?php echo htmlspecialchars($contact_email); ?></span>
        </a>
    </address>
</div>

</div>
</div>
</div>
</div>

<?php else: ?>
    <p class="error" style="text-align:center; margin:50px 0; color:red;">
        You must login to contact us. <br><br>
        <a href="login.php" style="color:red;text-decoration: underline;">Login</a>
    </p>
<?php endif; ?>

<script src="assets/js/contact.js"></script>
<?php require_once('footer.php'); ?>
