<?php
require_once 'db.php';

// Get all posts
function getAllPosts() {
    global $conn;
    $stmt = $conn->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.author_id = users.user_id ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get a single post by ID
function getPostById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.author_id = users.user_id WHERE post_id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createPost($author_id, $title, $content) {
    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'uploads/';
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $imageName;

        // Move uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            $imagePath = NULL;
        }
    } else {
        $imagePath = NULL;
    }

    // Insert post data into the database
    global $conn;
    $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, image_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $content, $author_id, $imagePath]);
}


// Update an existing post
function updatePost($postId, $title, $content) {
    global $conn;
    $stmt = $conn->prepare("UPDATE posts SET title = :title, content = :content WHERE post_id = :id");
    $stmt->execute(['id' => $postId, 'title' => $title, 'content' => $content]);
}

// Delete a post
function deletePost($postId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM posts WHERE post_id = :id");
    $stmt->execute(['id' => $postId]);
}


// Check if the user already liked the post
function hasLikedPost($postId, $userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

// Add a like to a post
function likePost($postId, $userId) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)");
    $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
}

// Remove a like from a post
function unlikePost($postId, $userId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
}

// Count likes for a post
function countLikes($postId) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $postId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_likes'];
}
// Count likes for a post
function countComments($postId) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) AS total_comments FROM comments WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $postId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_comments'];
}
?>
