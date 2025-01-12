<?php
require 'includes/db.php';
require 'includes/post_functions.php';

$posts = getAllPosts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogging Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
   
    <?php require_once 'partials/header.php'?>
    <div class="post-section">
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Welcome to the Blogging Platform</h1>

        <?php if (!empty($posts)): ?>
            <div class="row g-4">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="post-card d-flex flex-column h-100">
                            <?php if ($post['image_path']): ?>
                                <img src="<?= $post['image_path'] ?>" alt="Post Image" class="card-img-top post-image">
                            <?php endif; ?>

                            <div class="card-body d-flex flex-column">
                                <h2 class="post-title"><?= $post['title'] ?></h2>
                                <p class="post-meta">By <?= $post['username'] ?> on <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
                                <p class="card-text"><?= substr($post['content'], 0, 100) ?>...</p>
                                <?php $likeCount = countLikes($post['post_id']); ?>
                                <!-- Like and Comment Section -->
                                <div class="post-stats mt-3 d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="bi bi-heart-fill text-danger"></i>
                                        <strong><?= $likeCount ?></strong> Likes
                                    </span>
                                    <?php $commentCount = countComments($post['post_id']); ?>
                                    <span>
                                        <i class="bi bi-chat-fill text-primary"></i>
                                        <strong><?= $commentCount?></strong> Comments
                                    </span>
                                </div>

                                <!-- Read More Button -->
                                <a href="view_post.php?id=<?= $post['post_id'] ?>" class="btn btn-read-more mt-auto">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No posts available. <a href="create_post.php">Create one now!</a></p>
        <?php endif; ?>
    </div>
</div>


    <?php include 'partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

