<footer class="py-4">
    <style>
        footer {
            background-color: #2F3C7E; /* Dark blue background */
            color: white; /* White text */
        }

        footer p {
            margin: 0;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }

        footer ul {
            list-style: none;
            padding: 0;
            margin: 10px 0 0;
        }

        footer ul li {
            display: inline;
            margin: 0 10px;
        }

        footer ul li a {
            color: #FBEAEB; /* Light pink for links */
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        footer ul li a:hover {
            color: #FFFFFF; /* White on hover */
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .social-icons a {
            background-color: white !important;
            color: #2F3C7E !important; /* Dark blue icons */
            border-radius: 50%;
            padding: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: background-color 0.3s, color 0.3s;
        }

        .social-icons a:hover {
            background-color: #FBEAEB; /* Light pink on hover */
            color: #2F3C7E; /* Dark blue icon on hover */
        }
    </style>

    <div class="container text-center">
        <p>&copy; <?= date('Y') ?> Blogging Platform. All Rights Reserved.</p>
        <div class="social-icons">
            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        </div>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy Policy</a></li>
            <li class="list-inline-item"><a href="#">Terms of Service</a></li>
            <li class="list-inline-item"><a href="#">Contact Us</a></li>
        </ul>
    </div>
</footer>

<!-- Include Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
