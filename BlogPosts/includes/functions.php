<?php

// Redirect to a different page
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sanitize user input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Display flash messages
function displayMessage($type, $message) {
    echo "<div class='alert alert-$type'>$message</div>";
}
?>
