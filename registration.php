<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
}
?>

<?php
if (isset($_POST['form1'])) {

    $error_message = '';
    $success_message = '';
    $valid = 1;

    // Validate Name
    if(empty($_POST['cust_name'])) {
        $valid = 0;
        $error_message .= "Customer Name can not be empty.<br>";
    } elseif(!preg_match("/^[a-zA-Z ]+$/", $_POST['cust_name'])) {
        $valid = 0;
        $error_message .= "Customer Name can only contain alphabets.<br>";
    }

    // Validate Email
    if(empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= "Email Address can not be empty.<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= "Email address must be valid.<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message .= "Email Address Already Exists.<br>";
            }
        }
    }

    // Validate Phone
    if(empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= "Phone Number can not be empty.<br>";
    } elseif(!preg_match("/^\+?[0-9]{1,17}$/", $_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= "Invalid Phone Number.<br>";
    }

    // Validate Address
    if(empty($_POST['cust_address'])) {
        $valid = 0;
        $error_message .= "Address can not be empty.<br>";
    }

    // Validate City
    if(empty($_POST['cust_city'])) {
        $valid = 0;
        $error_message .= "City can not be empty.<br>";
    } elseif(!preg_match("/^[a-zA-Z ]+$/", $_POST['cust_city'])) {
        $valid = 0;
        $error_message .= "City can only contain alphabets.<br>";
    }

    // Validate State
    if(empty($_POST['cust_state'])) {
        $valid = 0;
        $error_message .= "State can not be empty.<br>";
    } elseif(!preg_match("/^[a-zA-Z ]+$/", $_POST['cust_state'])) {
        $valid = 0;
        $error_message .= "State can only contain alphabets.<br>";
    }

    // Validate Zip Code
    if(empty($_POST['cust_zip'])) {
        $valid = 0;
        $error_message .= "Pin Code can not be empty.<br>";
    } elseif(!preg_match("/^[0-9]+$/", $_POST['cust_zip'])) {
        $valid = 0;
        $error_message .= "Zip Code can only contain digits.<br>";
    }

    // Validate Password
    if(empty($_POST['cust_password']) || empty($_POST['cust_re_password'])) {
        $valid = 0;
        $error_message .= "Password can not be empty.<br>";
    } elseif($_POST['cust_password'] != $_POST['cust_re_password']) {
        $valid = 0;
        $error_message .= "Passwords do not match.<br>";
    }

    // Insert if valid
    if($valid == 1) {

        $statement = $pdo->prepare("INSERT INTO tbl_customer (
                                        cust_name,                                       
                                        cust_email,
                                        cust_phone,                                  
                                        cust_address,
                                        cust_city,
                                        cust_state,
                                        cust_zip,                                                                               
                                        cust_s_name,                                       
                                        cust_s_phone,                                       
                                        cust_s_address,
                                        cust_s_city,
                                        cust_s_state,
                                        cust_s_zip,
                                        cust_password,                                       
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
            strip_tags($_POST['cust_name']),                                      
            strip_tags($_POST['cust_email']),
            strip_tags($_POST['cust_phone']),                                      
            strip_tags($_POST['cust_address']),
            strip_tags($_POST['cust_city']),
            strip_tags($_POST['cust_state']),
            strip_tags($_POST['cust_zip']),                                                                               
            '', '', '', '', '', '',                                                                               
            ($_POST['cust_password']),
            1
        ));

        $success_message = "Your registration is completed successfully!";
    }
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="inner">
        <h1>Customer Registration</h1>
    </div>
</div>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="user-content">
                    <?php
                    if(isset($error_message) && $error_message != '') {
                        echo "<div class='error' style='padding:10px;background:#f1f1f1;margin-bottom:20px;'>".$error_message."</div>";
                    }
                    if(isset($success_message) && $success_message != '') {
                        echo "<div class='success' style='padding:10px;background:#f1f1f1;margin-bottom:20px;'>".$success_message."</div>";
                    }
                    ?>
                    <form action="" method="post" id="registrationForm">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">

                                <div class="col-md-6 form-group">
                                    <label>Full Name *</label>
                                    <input type="text" class="form-control" name="cust_name" id="cust_name" value="<?php if(isset($_POST['cust_name'])) echo $_POST['cust_name']; ?>">
                                    <span class="error-message" id="error-name"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Email Address *</label>
                                    <input type="email" class="form-control" name="cust_email" id="cust_email" value="<?php if(isset($_POST['cust_email'])) echo $_POST['cust_email']; ?>">
                                    <span class="error-message" id="error-email"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Phone Number *</label>
                                    <input type="text" class="form-control" name="cust_phone" id="cust_phone" value="<?php if(isset($_POST['cust_phone'])) echo $_POST['cust_phone']; ?>">
                                    <span class="error-message" id="error-phone"></span>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label>Address *</label>
                                    <textarea name="cust_address" class="form-control" style="height:70px;"><?php if(isset($_POST['cust_address'])) echo $_POST['cust_address']; ?></textarea>
                                    <span class="error-message" id="error-address"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>City *</label>
                                    <input type="text" class="form-control" name="cust_city" id="cust_city" value="<?php if(isset($_POST['cust_city'])) echo $_POST['cust_city']; ?>">
                                    <span class="error-message" id="error-city"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>State *</label>
                                    <input type="text" class="form-control" name="cust_state" id="cust_state" value="<?php if(isset($_POST['cust_state'])) echo $_POST['cust_state']; ?>">
                                    <span class="error-message" id="error-state"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Zip Code *</label>
                                    <input type="text" class="form-control" name="cust_zip" id="cust_zip" value="<?php if(isset($_POST['cust_zip'])) echo $_POST['cust_zip']; ?>">
                                    <span class="error-message" id="error-zip"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Password *</label>
                                    <input type="password" class="form-control" name="cust_password" id="cust_password">
                                    <span class="error-message" id="error-password"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Retype Password *</label>
                                    <input type="password" class="form-control" name="cust_re_password" id="cust_re_password">
                                    <span class="error-message" id="error-repassword"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <input type="submit" class="btn btn-danger" value="Register" name="form1">
                                </div>

                            </div>
                        </div>                        
                    </form>
                </div>                
            </div>
        </div>
    </div>
</div>

<script src="assets/js/custom-validation.js"></script>
<?php require_once('footer.php'); ?>
