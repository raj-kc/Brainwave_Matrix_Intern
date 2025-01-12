<?php
require_once 'db.php';

// Get all comments for a post
function getCommentsByPostId($postId) {
    global $conn;
    $stmt = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = :post_id ORDER BY created_at DESC");
    $stmt->execute(['post_id' => $postId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add a comment to the database
function addComment($postId, $userId, $commentText) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment_text) VALUES (:post_id, :user_id, :comment_text)");
    $stmt->execute(['post_id' => $postId, 'user_id' => $userId, 'comment_text' => $commentText]);
}




?>
