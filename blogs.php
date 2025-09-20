<?php
require_once('header.php');

try {
    $statement = $pdo->prepare("SELECT b.*, c.cust_name FROM tbl_blogs b JOIN tbl_customer c ON b.user_id = c.cust_id ORDER BY b.likes DESC");
    $statement->execute();
    $blogs = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<h2 style="text-align:center; margin-top:20px;">All Blogs</h2>

<div style="padding:20px;">
    <?php foreach($blogs as $blog): ?>
        <div style="display:flex; background-color: #d1e7dd; margin-bottom:20px; padding:10px; border-radius:10px;">
            <img src="uploads/<?php echo htmlspecialchars($blog['image']); ?>" alt="blog" style="width:150px; height:100px; border-radius:10px;">
            <div style="flex:1; padding-left:20px;">
                <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                <p><?php echo htmlspecialchars($blog['description']); ?></p>
                <div style="text-align:right;">
                    <small>By: <?php echo htmlspecialchars($blog['cust_name']); ?></small><br>
                    <form action="like_dislike.php" method="POST" style="display:inline;">
                        <input type="hidden" name="blog_id" value="<?php echo $blog['blog_id']; ?>">
                        <button type="submit" name="like">👍 <?php echo $blog['likes']; ?></button>
                        <button type="submit" name="dislike">👎 <?php echo $blog['dislikes']; ?></button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div style="text-align:center; margin:20px;">
    <a href="uploadblog.php" style="padding:10px 20px; background-color:#28a745; color:white; text-decoration:none; border-radius:5px;">Upload New Blog</a>
</div>

<?php require_once('footer.php'); ?>
