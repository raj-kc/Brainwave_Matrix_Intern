<?php
require_once 'db.php';
require_once 'functions.php';

session_start();

// User Login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        redirect('../index.php');
    } else {
        displayMessage('danger', 'Invalid email or password.');
    }
}

// User Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    redirect('../index.php');
}
?>
