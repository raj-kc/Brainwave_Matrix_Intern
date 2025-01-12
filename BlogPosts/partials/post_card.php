<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
        <p class="card-text"><?= substr(htmlspecialchars($post['content']), 0, 150) ?>...</p>
        <small class="text-muted">By <?= htmlspecialchars($post['username']) ?> on <?= date('F d, Y', strtotime($post['created_at'])) ?></small>
        <a href="post.php?id=<?= $post['post_id'] ?>" class="btn btn-primary btn-sm float-end">Read More</a>
    </div>
</div>
