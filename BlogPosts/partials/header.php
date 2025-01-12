<?php session_start(); 
require './includes/db.php';
?>


<nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">Blogging Platform</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="create_post.php">Create Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./includes/auth.php?logout=true">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Get Started</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
