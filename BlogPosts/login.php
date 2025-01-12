<?php
// require 'partials/header.php';
require 'includes/auth.php';
?>

<div class="auth-container">
        <h2>Login</h2>
        <form method="post" action="./includes/auth.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            <div class="separator"><span>or</span></div>
            <button type="button" class="google-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" alt="Google Logo"> Login with Google
            </button>
        </form>
    </div>
<?php include 'partials/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

