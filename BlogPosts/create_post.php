<?php
require_once 'partials/header.php';
require_once 'includes/post_functions.php';
require_once 'includes/functions.php';
require_once 'vendor/autoload.php';  // This includes all installed libraries, including HTMLPurifier

if (!isLoggedIn()) {
    redirect('login.php');
}

function sanitizeTitleContent($input) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($input); // Sanitize the HTML content
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeTitleContent($_POST['title']);
    $content = sanitizeTitleContent($_POST['content']);
    // Validate the input fields
    if (empty($title) || empty($content)) {
        $error = "Title and content cannot be empty!";
    } else {
        createPost($_SESSION['user_id'], $title, $content, $imagePath);
        redirect('index.php');
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Post</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/main.css">

<style>
    .create-post-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 700px;
        margin: auto;
    }

    .create-post-title {
        color: #2F3C7E;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
    }

    .create-post-label {
        color: #2F3C7E;
        font-weight: 500;
        font-family: 'Poppins', sans-serif;
    }

    .create-post-input {
        border: 1px solid #2F3C7E;
        border-radius: 5px;
        padding: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .create-post-input:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(47, 60, 126, 0.5);
        border-color: #2F3C7E;
    }

    .create-post-editor {
        border: 1px solid #2F3C7E;
        border-radius: 5px;
        min-height: 300px;
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
    }

    .create-post-submit {
        background-color: #2F3C7E;
        color: white;
        border: none;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        font-weight: 500;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .create-post-submit:hover {
        background-color: #1C2858;
    }

    @media (max-width: 768px) {
        .create-post-container {
            padding: 20px;
        }

        .create-post-editor {
            min-height: 200px;
            max-height: 200px;
        }
    }

    @media (max-width: 576px) {
        .create-post-container {
            padding: 15px;
        }
    }
</style>

<div class="container create-post-container py-5">
    <h2 class="create-post-title text-center mb-4">Create a New Post</h2>
    <form method="post" action="create_post.php" enctype="multipart/form-data" class="create-post-form">
        <div class="mb-3">
            <label for="title" class="form-label create-post-label">Title</label>
            <input type="text" name="title" id="title" class="form-control create-post-input" placeholder="Enter your post title here..." required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label create-post-label">Content</label>
            <div id="content" class="create-post-editor"></div>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label create-post-label">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control create-post-input" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary w-100 create-post-submit">Create Post</button>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Quill Editor with an enhanced toolbar
        const quill = new Quill('#content', {
            theme: 'snow',
            placeholder: 'Write something amazing...',
            modules: {
                toolbar: [
                    // Text styling
                    ['bold', 'italic', 'underline', 'strike'], 
                    ['blockquote', 'code-block'],  
                    
                    // Headers
                    [{ 'header': 1 }, { 'header': 2 }], 
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                    // Lists and indents
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }], 
                    
                    // Script and alignment
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'align': [] }],

                    // Font size and font family
                    [{ 'size': ['small', false, 'large', 'huge'] }], 
                    [{ 'font': [] }],

                    // Colors
                    [{ 'color': [] }, { 'background': [] }],

                    // Direction and other formatting
                    [{ 'direction': 'rtl' }], 
                    
                    // Media
                    ['link', 'image', 'video'], 

                    // Clean formatting
                    ['clean'] 
                ]
            }
        });

        // Handle form submission to include rich-text data
        const form = document.querySelector('form');
        form.addEventListener('submit', () => {
            const contentInput = document.createElement('input');
            contentInput.setAttribute('type', 'hidden');
            contentInput.setAttribute('name', 'content');
            contentInput.value = quill.root.innerHTML;
            form.appendChild(contentInput);
        });
    });

    // Handle image upload
quill.getModule('toolbar').addHandler('image', function() {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.click();

    input.onchange = function() {
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var imageUrl = e.target.result;
            var range = quill.getSelection();
            quill.insertEmbed(range.index, 'image', imageUrl);
        };

        reader.readAsDataURL(file);
    };
});
</script>

<?php include 'partials/footer.php'; ?>
