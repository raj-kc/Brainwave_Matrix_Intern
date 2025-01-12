<?php
require 'includes/db.php';
require 'includes/functions.php';
// require 'partials/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);

    // Fetch the newly created user
    $stmt = $conn->prepare("SELECT user_id, username FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Store user details in the session
    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        redirect('index.php');
    } else {
        // Handle error if user couldn't be fetched
        echo "Error: Unable to retrieve user data.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/sign_in_sign_up.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-container active" id="login-container">
        <div class="branding">MyApp</div>
        <h2>Login</h2>
        <button class="google-btn">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"> Login with Google
        </button>
        <div class="separator"><span>OR</span></div>
        <form method="post" action="./includes/auth.php">
            <div class="mb-3">
                <label for="login-email" class="form-label">Email</label>
                <input type="email" name="email" id="login-email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="login-password" class="form-label">Password</label>
                <input type="password" name="password" id="login-password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
        <a href="#" class="switch-link" id="switch-to-register">Don't have an account? Register</a>
    </div>

    <div class="auth-container" id="register-container">
        <div class="branding">MyApp</div>
        <h2>Register</h2>
        <button class="google-btn">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"> Sign up with Google
        </button>
        <div class="separator"><span>OR</span></div>
        <form method="post" action="register.php">
            <div class="mb-3">
                <label for="register-username" class="form-label">Username</label>
                <input type="text" name="username" id="register-username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="register-email" class="form-label">Email</label>
                <input type="email" name="email" id="register-email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="register-password" class="form-label">Password</label>
                <input type="password" name="password" id="register-password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <a href="#" class="switch-link" id="switch-to-login">Already have an account? Login</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const loginContainer = document.getElementById('login-container');
        const registerContainer = document.getElementById('register-container');
        const switchToRegister = document.getElementById('switch-to-register');
        const switchToLogin = document.getElementById('switch-to-login');

        switchToRegister.addEventListener('click', () => {
            loginContainer.classList.remove('active');
            registerContainer.classList.add('active');
        });

        switchToLogin.addEventListener('click', () => {
            registerContainer.classList.remove('active');
            loginContainer.classList.add('active');
        });
    </script>
</body>
</html>
