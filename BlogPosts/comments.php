<?php
require_once 'includes/comment_functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['comment_text'], $_SESSION['user_id'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];
    $commentText = trim($_POST['comment_text']);

    if (!empty($commentText)) {
        // Add the comment to the database
        addComment($postId, $userId, $commentText);

        // Fetch the newly added comment
        global $conn;
        $stmt = $conn->prepare("SELECT comments.comment_text, comments.created_at, users.username 
                                FROM comments 
                                JOIN users ON comments.user_id = users.user_id 
                                WHERE post_id = :post_id AND comments.user_id = :user_id 
                                ORDER BY comments.created_at DESC LIMIT 1");
        $stmt->execute(['post_id' => $postId, 'user_id' => $userId]);
        $newComment = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the new comment as JSON
        echo json_encode([
            'success' => true,
            'comment' => $newComment
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Comment text cannot be empty.']);
    }
    exit();
}

// If the request is invalid
http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Invalid request.']);
?>
