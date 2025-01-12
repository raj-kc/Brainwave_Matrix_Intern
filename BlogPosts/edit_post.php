<?php
require_once 'partials/header.php';
require_once 'includes/post_functions.php';
require_once 'includes/functions.php';
require_once 'vendor/autoload.php';  // This includes all installed libraries, including HTMLPurifier

if (!isLoggedIn()) {
    redirect('login.php');
}

// Fetch the post data from the database based on the post_id
if (isset($_GET['post_id'])) {
    $postId = $_GET['post_id'];
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM posts WHERE post_id = ? AND author_id = ?");
    $stmt->execute([$postId, $_SESSION['user_id']]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        // If the post does not exist, redirect to profile
        header('Location: profile.php');
        exit;
    }
} else {
    // If post_id is not passed, redirect to profile
    header('Location: profile.php');
    exit;
}

// Function to sanitize HTML content (escape HTML tags for title only)
function sanitizeHtml($content) {
    return htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
}

// Image upload handling
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    // Validate and handle image upload
    $imagePath = $post['image'];  // default to the current image path

    if ($image['error'] === UPLOAD_ERR_OK) {
        // If a new image is uploaded, process it
        $imageTmpName = $image['tmp_name'];
        $imageName = basename($image['name']);
        $imageDirectory = 'uploads/'; // Specify the directory where images should be uploaded
        $imagePath = $imageDirectory . $imageName;
        
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Update the image path if upload is successful
            $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ?, image = ? WHERE post_id = ?");
            $stmt->execute([$title, $content, $imagePath, $postId]);
        }
    } else {
        // Update post without changing the image
        $stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE post_id = ?");
        $stmt->execute([$title, $content, $postId]);
    }

    // Redirect after updating
    header('Location: profile.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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
</head>
<body>

<div class="container create-post-container py-5">
    <h2 class="create-post-title text-center mb-4">Edit this Post</h2>
    <form method="post" action="edit_post.php?post_id=<?= $post['post_id'] ?>" enctype="multipart/form-data" class="create-post-form">
        <div class="mb-3">
            <label for="title" class="form-label create-post-label">Title</label>
            <input type="text" name="title" id="title" class="form-control create-post-input" value="<?= sanitizeHtml($post['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label create-post-label">Content</label>
            <div id="content" class="create-post-editor"><?= $post['content'] ?></div>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label create-post-label">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control create-post-input" accept="image/*">
            <?php if ($post['image_path']): ?>
                <div class="mt-3">
                    <img src="<?= $post['image_path'] ?>" alt="Post Image" class="img-fluid" style="max-width: 200px;">
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary w-100 create-post-submit">Save</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize Quill editor with current post content
        const quill = new Quill('#content', {
            theme: 'snow',
            placeholder: 'Write something amazing...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    [{ 'align': [] }],
                    [{ 'size': ['small', false, 'large', 'huge'] }],
                    [{ 'font': [] }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'direction': 'rtl' }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        // Set the editor's content to the current post's content (rich-text)
        const postContent = `<?= addslashes($post['content']) ?>`;
        quill.root.innerHTML = postContent;

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
</script>

<?php include 'partials/footer.php'; ?>

</body>
</html>
