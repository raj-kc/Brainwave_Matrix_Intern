<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/post_functions.php';
require_once 'partials/header.php';
$userId = $_SESSION['user_id'];

global $conn;
// Fetch user details
$stmt = $conn->prepare("SELECT username, email FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user's posts
$postStmt = $conn->prepare("SELECT * FROM posts WHERE author_id = ?");
$postStmt->execute([$userId]);
$posts = $postStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));

    // If you have a 'bio' column, you can include it here
    $bio = isset($_POST['bio']) ? htmlspecialchars(trim($_POST['bio'])) : null;

    // Update the user information
    $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    if ($bio) {
        $updateStmt = $conn->prepare("UPDATE users SET username = ?, email = ?, bio = ? WHERE user_id = ?");
        $updateStmt->execute([$username, $email, $bio, $userId]);
    } else {
        $updateStmt->execute([$username, $email, $userId]);
    }

    // Update the $user array with the new values
    $user['username'] = $username;
    $user['email'] = $email;
    $user['bio'] = $bio;

    $message = "Profile updated successfully.";
}

// Handle post deletion
if (isset($_GET['delete_post'])) {
    $postId = intval($_GET['delete_post']);
    $deleteStmt = $conn->prepare("DELETE FROM posts WHERE post_id = ? AND author_id = ?");
    $deleteStmt->execute([$postId, $userId]);
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    

    <div class="container mt-5">
        <h1 class="mb-4">User Dashboard</h1>

        <!-- Profile Section -->
        <div class="card mb-5">
            <div class="card-header">Update Profile</div>
            <div class="card-body">
                <?php if (isset($message)): ?>
                    <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST" action="profile.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?= $user['username'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>" required>
                    </div>

                    <!-- Bio Section (Optional) -->
                    <?php if (isset($user['bio'])): ?>
                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea name="bio" id="bio" class="form-control" rows="3"><?= $user['bio'] ?></textarea>
                        </div>
                    <?php endif; ?>

                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- User Posts Section -->
        <h2 class="mb-4">Your Posts</h2>
        <div class="row g-4">
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <img src="<?= $post['image_path'] ?>" class="card-img-top" alt="Post Image">
                            <div class="card-body d-flex flex-column">
                                <h2 class="post-title"><?= $post['title'] ?></h2>
                                <p class="post-meta">By <?= $user['username'] ?> on <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
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
                                <div class="d-flex justify-content-start">
    <a href="edit_post.php?post_id=<?= $post['post_id'] ?>" class="btn btn-warning btn-sm me-2">Edit</a>
    <a href="profile.php?delete_post=<?= $post['post_id'] ?>" 
       onclick="return confirm('Are you sure you want to delete this post?');" 
       class="btn btn-danger btn-sm">Delete</a>
</div>

                                
                              </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No posts available. <a href="create_post.php">Create one now!</a></p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
