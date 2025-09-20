<?php
require_once('header.php');

if(!isset($_SESSION['customer'])) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['customer']['cust_id'];

try {
    $statement = $pdo->prepare("SELECT * FROM tbl_blogs WHERE user_id = ? ORDER BY created_at DESC");
    $statement->execute([$user_id]);
    $blogs = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<h2 style="text-align:center; margin-top:20px;">My Blogs</h2>

<div class="col-md-12"> 
                <?php require_once('customer-sidebar.php'); ?>
            </div>

<div style="text-align:center; margin:20px;">
    <a href="uploadblog.php" style="padding:10px 20px; background-color:#28a745; color:white; text-decoration:none; border-radius:5px;">Upload New Blog</a>
</div>

<div style="padding:20px;">
    <?php foreach($blogs as $blog): ?>
        <div style="display:flex; background-color: #d1e7dd; margin-bottom:20px; padding:10px; border-radius:10px;">
            <img src="uploads/<?php echo htmlspecialchars($blog['image']); ?>" alt="blog" style="width:150px; height:100px; border-radius:10px;">
            <div style="flex:1; padding-left:20px;">
                <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                <p><?php echo htmlspecialchars($blog['description']); ?></p>
            </div>
            <div>
                <form action="deleteblog.php" method="POST">
                    <input type="hidden" name="blog_id" value="<?php echo $blog['blog_id']; ?>">
                    <button type="submit" style="background:none; border:none; color:red;">🗑️ Delete</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once('footer.php'); ?>
