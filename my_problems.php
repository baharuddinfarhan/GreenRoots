<?php require_once('header.php'); ?>

<?php
if(!isset($_SESSION['customer'])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['customer']['cust_id'];
$stmt = $pdo->prepare("SELECT * FROM tbl_problems WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$problems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="text-align:center; margin-top:20px; color:#4f806b;">My Problems & Expert Advices</h2>
<div class="col-md-12"> 
                <?php require_once('customer-sidebar.php'); ?>
            </div>
<?php foreach($problems as $problem): ?>
<div style="display:flex; justify-content:space-between; background:#4f806b; color:white; border-radius:8px; padding:15px; margin:20px auto; max-width:1000px; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
    
    <!-- Left: Problem Details -->
    <div style="width:48%;">
        <h3 style="margin-top:0;"><?php echo htmlspecialchars($problem['title']); ?></h3>
        <?php if($problem['image']): ?>
            <img src="uploads/<?php echo $problem['image']; ?>" style="max-width:200px; border-radius:5px;">
        <?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars($problem['description'])); ?></p>
    </div>

    <!-- Right: Advices -->
    <div style="width:48%; background:#3e6555; padding:10px; border-radius:5px;">
        <h4 style="margin-top:0;">Advices:</h4>
        <?php
        $stmt2 = $pdo->prepare("SELECT a.*, c.cust_name FROM tbl_advices a JOIN tbl_customer c ON a.user_id = c.cust_id WHERE problem_id=? ORDER BY love_count DESC, created_at DESC");
        $stmt2->execute([$problem['problem_id']]);
        $advices = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        if(count($advices) == 0):
        ?>
            <p>No advices yet.</p>
        <?php
        else:
            foreach($advices as $advice):
        ?>
            <div style="margin-bottom:10px; border-bottom:1px solid white; padding-bottom:5px;">
                <p><?php echo nl2br(htmlspecialchars($advice['advice'])); ?></p>
                <p><strong>- <?php echo htmlspecialchars($advice['cust_name']); ?></strong></p>
                <p style="color:#f8d7da;">❤️ <?php echo $advice['love_count']; ?></p>
            </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</div>
<?php endforeach; ?>

<?php require_once('footer.php'); ?>
