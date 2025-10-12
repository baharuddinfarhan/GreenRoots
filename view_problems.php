<?php require_once('header.php'); ?>

<h2 style="text-align:center; margin-top:20px; color:#4f806b;">Expert Advice Board</h2>

<?php
$statement = $pdo->prepare("SELECT p.*, c.cust_name FROM tbl_problems p JOIN tbl_customer c ON p.user_id = c.cust_id ORDER BY p.created_at DESC");
$statement->execute();
$problems = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach($problems as $problem):
?>
<div style="background:#4f806b; color:white; border-radius:10px; padding:20px; margin:20px auto; max-width:800px; box-shadow:0 0 10px rgba(0,0,0,0.3);">
    
    <h3><?php echo htmlspecialchars($problem['title']); ?></h3>
    <p><strong>Posted by:</strong> <?php echo htmlspecialchars($problem['cust_name']); ?></p>

    <div style="display:flex; align-items:center;">
        <?php if($problem['image']): ?>
            <img src="uploads/<?php echo $problem['image']; ?>" style="max-width:200px; border-radius:5px; margin-right:20px;">
        <?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars($problem['description'])); ?></p>
    </div>

    <!-- Display advices sorted by love_count -->
    <h4>Advices:</h4>
    <?php
    $stmt = $pdo->prepare("SELECT a.*, c.cust_name FROM tbl_advices a JOIN tbl_customer c ON a.user_id = c.cust_id WHERE problem_id=? ORDER BY love_count DESC, created_at DESC");
    $stmt->execute([$problem['problem_id']]);
    $advices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($advices as $advice):
    ?>
        <div style="background:white; color:#4f806b; margin:10px 0; padding:10px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.2);">
            <p><?php echo nl2br(htmlspecialchars($advice['advice'])); ?></p>
            <p><strong>- <?php echo htmlspecialchars($advice['cust_name']); ?></strong></p>
            <form method="post" action="love_advice.php" style="display:inline;">
                <input type="hidden" name="advice_id" value="<?php echo $advice['advice_id']; ?>">
                <button type="submit" style="border:none; background:none; color:red; font-size:16px;">❤️ <?php echo $advice['love_count']; ?></button>
            </form>
        </div>
    <?php endforeach; ?>

    <!-- Give advice form -->
    <form method="post" action="submit_advice.php" style="margin-top:10px;">
        <input type="hidden" name="problem_id" value="<?php echo $problem['problem_id']; ?>">
        <textarea name="advice" placeholder="Write your advice..." required style="width:100%; padding:10px; border-radius:5px; border:none; color:#4f806b;"></textarea><br>
        <button type="submit" style="padding:10px 20px; background:white; color:#4f806b; border:none; border-radius:5px; margin-top:5px;">Submit Advice</button>
    </form>
</div>
<?php endforeach; ?>

<div style="text-align:center; margin:20px;">
    <a href="upload_problem.php" style="padding:10px 20px; background:green; color:white; border-radius:5px; text-decoration:none;">Need Advices?</a>
</div>

<?php require_once('footer.php'); ?>
