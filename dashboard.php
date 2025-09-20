<?php
require_once('header.php');

// Ensure error reporting is on during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the customer is logged in or not (from dashboard.php)
if (!isset($_SESSION['customer'])) {
    header('location: ' . BASE_URL . 'logout.php'); // Assuming BASE_URL is defined in config or header.php
    exit;
} else {
    try {
        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
        $statement->execute(array($_SESSION['customer']['cust_id'], 0));
        $total = $statement->rowCount();
        if ($total) {
            header('location: ' . BASE_URL . 'logout.php');
            exit;
        }
    } catch (PDOException $e) {
        error_log("Database error during customer status check: " . $e->getMessage());
        // Optionally redirect or show an error to the user
        header('location: ' . BASE_URL . 'logout.php?error=db_error');
        exit;
    }
}

// Fetch user data for profile display (from myprofile.php)
$user = []; // Initialize user array
$user_id = $_SESSION['customer']['cust_id'];

try {
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id = ?");
    $statement->execute([$user_id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // If user not found, log out
        header('location: ' . BASE_URL . 'logout.php?error=user_not_found');
        exit;
    }
} catch (PDOException $e) {
    error_log("Database error fetching user profile: " . $e->getMessage());
    echo "<p style='color:red; text-align:center;'>Error loading profile data. Please try again later.</p>";
    // You might want to handle this more gracefully, e.g., redirect to an error page or show a generic message.
    $user['cust_name'] = 'N/A'; // Fallback
    $user['cust_email'] = 'N/A'; // Fallback
}
?>

<div class="page" style=" background-color:antiquewhite;">
    <div class="container">
        <div class="row">
            <div class="col-md-12" >
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            <div class="col-md-12">
                <div class="user-content" style="padding: 20px; background-color: #4f806b; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align:center;">
                    <h3 class="text-center" style="font-size: 2em; color:whitesmoke; margin-bottom: 25px;">
                        <?php echo "Welcome to Your Profile, " . htmlspecialchars($user['cust_name']) . "!"; ?>
                    </h3>

                    <h2 style="text-align:center; margin-top:30px; margin-bottom: 20px; color:whitesmoke;">My Profile Details</h2>
                    <div style="padding:15px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; margin-bottom: 30px;">
                        <p style="font-size: 1.1em; color:whitesmoke; margin-bottom: 10px;"><strong>Name:</strong> <?php echo htmlspecialchars($user['cust_name']); ?></p>
                        <p style="font-size: 1.1em; color:whitesmoke;"><strong>Email:</strong> <?php echo htmlspecialchars($user['cust_email']); ?></p>
                        <p style="font-size: 1.1em; color:whitesmoke;"><strong>Phone:</strong> <?php echo htmlspecialchars($user['cust_phone']); ?></p>
                        <p style="font-size: 1.1em; color:whitesmoke;"><strong>Address:</strong> <?php echo htmlspecialchars($user['cust_address']); ?></p>
                        <p style="font-size: 1.1em; color:whitesmoke;"><strong>State:</strong> <?php echo htmlspecialchars($user['cust_state']); ?></p>
                        <p style="font-size: 1.1em; color:whitesmoke;"><strong>City:</strong> <?php echo htmlspecialchars($user['cust_city']); ?></p>
                        <p style="font-size: 1.1em; color:whitesmoke;"><strong>Zip:</strong> <?php echo htmlspecialchars($user['cust_zip']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>