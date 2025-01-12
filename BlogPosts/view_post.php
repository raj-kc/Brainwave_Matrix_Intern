<?php
require_once 'includes/post_functions.php';
require_once 'includes/comment_functions.php';
require_once 'partials/header.php';

// Fetch the post by ID
$post = getPostById($_GET['id']);
$comments = getCommentsByPostId($post['post_id']);
$totalLikes = countLikes($post['post_id']);
$userHasLiked = isset($_SESSION['user_id']) ? hasLikedPost($post['post_id'], $_SESSION['user_id']) : false;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/main.css">
</head>
<style>
    /* Custom Post Title Styling */
    .post-title {
        font-family: 'Poppins', sans-serif;
        color: #2F3C7E;
        font-weight: 600;
        font-size: 2.5rem;
        margin-bottom: 20px;
        text-align: center;
    }

    /* Custom Post Content Styling */
    .post-content {
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        color: #333;
        line-height: 1.6;
        margin-bottom: 30px;
        text-align: justify;
    }

    /* Styling for Post Metadata */
    .post-meta {
        font-family: 'Poppins', sans-serif;
        font-size: 0.9rem;
        color: #2F3C7E;
        text-align: center;
        margin-top: 20px;
    }

    /* Styling for Images Inside Post Content */
    .post-content img {
        max-width: 50%;
        /* Limit the image width */
        height: auto;
        float: right;
        /* Align image to the right */
        margin-left: 20px;
        /* Add space between image and text */
        margin-bottom: 20px;
        /* Add space below the image */
        border-radius: 8px;
    }

    /* Comment Section Styling */
    .comment-section {
        background-color: #FBEAEB;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 40px;
    }

    /* Add some responsiveness */
    @media (max-width: 768px) {
        .post-title {
            font-size: 2rem;
        }

        .post-content {
            font-size: 0.95rem;
        }

        .post-meta {
            font-size: 0.85rem;
        }

        .comment-section {
            padding: 15px;
        }

        .post-content img {
            max-width: 100%;
            /* Allow image to take full width on small screens */
            float: none;
            /* Remove float on smaller screens */
            display: block;
            margin: 0 auto;
        }
    }

    /* Default style for the like icon */
.like-icon {
    color: #000; /* Default black color */
    transition: color 0.3s ease; /* Smooth color transition */
}

/* Hover effect */
.like-icon:hover {
    color: #dc3545; /* Red on hover */
}

/* Liked state */
.like-icon.liked {
    color: #dc3545; /* Red when liked */
}



</style>

<body>
    <div class="container mt-4">
        <!-- Post title -->
        <h1 class="post-title">
            <?= htmlspecialchars($post['title']) ?>
        </h1>

        <!-- Post content - displayed as saved in the database (with HTML) -->
        <div class="post-content">
            <!-- If an image exists in the database, display it -->
            <?php if (!empty($post['image_path'])): ?>
            <img src="<?= htmlspecialchars($post['image_path']) ?>" alt="Post Image">
            <?php endif; ?>

            <?= $post['content'] ?> <!-- Output the raw content (HTML), including images -->
        </div>


        <!-- like sections -->
        <div class="like-section">
    <?php if (isset($_SESSION['user_id'])): ?>
        <i id="like-icon" class="like-icon <?= $userHasLiked ? 'bi bi-heart-fill liked' : 'bi bi-heart' ?>" 
            data-liked="<?= $userHasLiked ? 'true' : 'false' ?>" 
            data-post-id="<?= $post['post_id'] ?>" 
            style="cursor: pointer; font-size: 1.5rem; transition: color 0.3s;">
        </i>
    <?php else: ?>
        <p class="text-muted">Please <a href="login.php">login</a> to like this post.</p>
    <?php endif; ?>
    <p class="mt-2"><strong>Likes: <span id="like-count">
                <?= $totalLikes ?>
            </span></strong></p>
</div>


        <!-- Post metadata -->
        <p class="post-meta">
            <small>By
                <?= htmlspecialchars($post['username']) ?> on
                <?= date('F d, Y', strtotime($post['created_at'])) ?>
            </small>
        </p>


        <div class="comments mt-4">
    <h4>Comments</h4>
    <?php if (isset($_SESSION['user_id'])): ?>
    <form id="comment-form" class="mt-3">
        <div class="mb-3">
            <textarea name="comment_text" id="comment-text" class="form-control" placeholder="Add a comment..."
                required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <?php else: ?>
    <p class="text-muted">Please <a href="login.php">login</a> to add a comment.</p>
    <?php endif; ?>
    <div id="comments-container">
        <?php foreach ($comments as $comment): ?>
        <div class="mb-3 comment-item">
            <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
            <p><?= htmlspecialchars($comment['comment_text']) ?></p>
            <small class="text-muted"><?= date('F d, Y h:i A', strtotime($comment['created_at'])) ?></small>
        </div>
        <?php endforeach; ?>
    </div>

    
</div>

        <?php include 'partials/footer.php'; ?>
    </div>
</body>
<script>
 document.addEventListener('DOMContentLoaded', () => {
    const likeIcon = document.getElementById('like-icon');
    const likeCount = document.getElementById('like-count');

    if (likeIcon) {
        likeIcon.addEventListener('click', () => {
            const postId = likeIcon.getAttribute('data-post-id');
            const isLiked = likeIcon.getAttribute('data-liked') === 'true';
            const action = isLiked ? 'unlike' : 'like';

            // Send AJAX request
            fetch('like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}&action=${action}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.likes !== undefined) {
                        // Update like count
                        likeCount.textContent = data.likes;

                        // Toggle icon state
                        likeIcon.classList.toggle('bi-heart', isLiked);
                        likeIcon.classList.toggle('bi-heart-fill', !isLiked);
                        likeIcon.classList.toggle('liked', !isLiked);

                        // Update data-liked attribute
                        likeIcon.setAttribute('data-liked', !isLiked);
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });
    }
});




    document.addEventListener('DOMContentLoaded', () => {
    const commentForm = document.getElementById('comment-form');
    const commentsContainer = document.getElementById('comments-container');

    if (commentForm) {
        commentForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const commentText = document.getElementById('comment-text').value;
            const postId = <?= json_encode($post['post_id']) ?>; // Get post ID from PHP

            if (commentText.trim() === '') {
                alert('Comment text cannot be empty.');
                return;
            }

            // Send comment via AJAX
            fetch('comments.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}&comment_text=${encodeURIComponent(commentText)}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Create a new comment element
                        const newComment = data.comment;
                        const commentItem = document.createElement('div');
                        commentItem.classList.add('mb-3', 'comment-item');
                        commentItem.innerHTML = `
                            <strong>${newComment.username}:</strong>
                            <p>${newComment.comment_text}</p>
                            <small class="text-muted">${new Date(newComment.created_at).toLocaleString()}</small>
                        `;

                        // Add the new comment to the container
                        commentsContainer.prepend(commentItem);

                        // Clear the textarea
                        document.getElementById('comment-text').value = '';
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });
    }
});
</script>

</html>