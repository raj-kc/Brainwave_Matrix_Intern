<?php
require 'includes/post_functions.php';

if (isset($_GET['id'])) {
    deletePost($_GET['id']);
    header('Location: index.php');
    exit();
}
?>
