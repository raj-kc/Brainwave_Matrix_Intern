<?php
require_once 'includes/post_functions.php';
session_start();

if (isset($_POST['post_id'], $_POST['action'], $_SESSION['user_id'])) {
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];

    if ($_POST['action'] === 'like') {
        if (!hasLikedPost($postId, $userId)) {
            likePost($postId, $userId);
        }
    } elseif ($_POST['action'] === 'unlike') {
        if (hasLikedPost($postId, $userId)) {
            unlikePost($postId, $userId);
        }
    }

    // Return the updated like count
    $updatedLikes = countLikes($postId);
    echo json_encode(['likes' => $updatedLikes]);
    exit();
}

// If something goes wrong, return an error response
http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
exit();
?>
