<?php
// Include the necessary functions
require_once './includes/post_functions.php';
require_once './includes/comment_functions.php';

// Check if the form is submitted to add a comment
if (isset($_POST['add_comment']) && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $postId = $_GET['id'];
    $commentText = $_POST['comment_text'];

    // Add the comment to the database
    addComment($postId, $userId, $commentText);

    // Redirect back to the post page
    header("Location: view_post.php?id=" . $postId);
    exit();
}

// Get the comments for the post
$postId = $_GET['id'];
$comments = getCommentsByPostId($postId);
?>
<?php if (isset($_SESSION['user_id'])): ?>
    <form action="view_post.php?id=<?= $postId ?>" method="post" class="mt-3">
        <div class="mb-3">
            <textarea name="comment_text" class="form-control" placeholder="Add a comment..." required></textarea>
        </div>
        <button type="submit" name="add_comment" class="btn btn-primary">Submit</button>
    </form>
<?php else: ?>
    <p class="text-muted">Please <a href="login.php">login</a> to add a comment.</p>
<?php endif; ?>
<div class="comments mt-4">
    <h5>Comments</h5>
    <?php foreach ($comments as $comment): ?>
        <div class="mb-3">
            <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
            <p><?= htmlspecialchars($comment['comment_text']) ?></p>
            <small class="text-muted"><?= date('F d, Y h:i A', strtotime($comment['created_at'])) ?></small>
        </div>
    <?php endforeach; ?>
</div>


